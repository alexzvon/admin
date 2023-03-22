<?php

namespace App\Repositories\Shop;

use App\Contracts\Repositories\Shop\SupplierRepositoryInterface;
use App\Models\Shop\Product\Product;
use App\Models\Shop\Product\ProductSupplier;
use App\Models\Shop\Supplier\QuadCRMSupplier;
use App\Repositories\Repository;
use App\Models\Shop\Supplier\Supplier;
use App\Services\Shop\SupplierService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use MosseboShopCore\Models\Base\BaseModel;

/**
 * Class SupplierRepository
 * @package App\Repositories\Shop
 */
class SupplierRepository extends Repository implements SupplierRepositoryInterface
{
    /**
     * @return string
     */
    protected function getModelClassName(): string
    {
        return Supplier::class;
    }

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByFilters(int $perPage): LengthAwarePaginator
    {
        return $this->model()->paginate($perPage);
    }

    /**
     * @return \Illuminate\Support\Collection|null
     */
    public function getAllIds(): ?\Illuminate\Support\Collection
    {
        return $this->model()->whereHas('quadCRMSupplier', function (Builder $query) {
            $query->where('status', true);
        })->get()->pluck('id');
    }

    /**
     * @return array
     */
    public function getJustAllProducts(): array
    {
        $arr = [];
        ini_set('memory_limit', '2048M');

        foreach (Product::query()->cursor() as $product) {
            $arr[] = $this->returnProductArray($product);
        }

        return $arr;
    }

    /**
     * @param int $id
     * @return QuadCRMSupplier|null
     */
    public function findById(int $id): ?QuadCRMSupplier
    {
        /**
         * @var QuadCRMSupplier|null $result
         */
        $result = QuadCRMSupplier::query()->where('supplier_id', $id)->first();

        return $result;
    }

    /**
     * @param array|null $suppliersId
     * @return array
     */
    public function getProductsBySuppliersId(?array $suppliersId): array
    {
        $productIds = ProductSupplier::query()->whereIn('supplier_id', $suppliersId)
            ->pluck('product_id');
        $arr = [];

        foreach (Product::query()->whereIn('id', $productIds)->cursor() as $product) {
            $arr[] = $this->returnProductArray($product);
        }

        return $arr;
    }

    /**
     * @param Product $product
     * @return array
     */
    public function returnProductArray(Product $product): array
    {
        return [
            'ID' => $product->id,
            'ID у поставщика' => $product->id_from_supplier,
            'Поставщики' => isset($product->suppliers()->first()->id) ?
                $product->suppliers()->first()->id  . '|' . $product->suppliers()->first()->name : null,
            'Старая цена' => $this->oldPrice($product),
            'Цена продажи' => $this->sellingPrice($product),
            'Акционная цена' => $this->discountingPrice($product),
            'Закупочная цена' => $this->purchasePrice($product),
            'Под заказ' => $product->onOrder,
            'В наличии' => $product->quantity,
            'Дата поступления' => $product->receiptDate,
            'Срок изготовления' => $product->production_time
        ];
    }

    /**
     * @param Product $product
     * @return int|null
     */
    public function oldPrice(Product $product): ?int
    {
        return $product->prices()->where('price_type_id', SupplierService::OLD_PRICE)
                ->first()->value ?? null;
    }

    /**
     * @param Product $product
     * @return int|null
     */
    public function sellingPrice(Product $product): ?int
    {
        return $product->prices()->where('price_type_id', SupplierService::SELLING_PRICE)
                ->first()->value ?? null;
    }

    /**
     * @param Product $product
     * @return int|null
     */
    public function discountingPrice(Product $product): ?int
    {
        return $product->prices()->where('price_type_id', SupplierService::DISCOUNTING_PRICE)
                ->first()->value ?? null;
    }

    /**
     * @param Product $product
     * @return int|null
     */
    public function purchasePrice(Product $product): ?int
    {
        return $product->prices()->where('price_type_id', SupplierService::PURCHASE_PRICE)
                ->first()->value ?? null;
    }

    public function updateOrCreateProduct(array $item)
    {
        DB::beginTransaction();

        $supplier = Supplier::query()->where('name', $item['postavshchiki'])->first() ?? null;
        $product = Product::find($item['id']);

        if (is_null($product)) {
            $this->createProduct($item, $supplier);
            DB::commit();

        } else {
            $this->updateProduct($product, $item, $supplier);
            DB::commit();
        }

        DB::rollBack();
    }

    public function createProduct(array $item, ?Supplier $supplier)
    {
        $supId = $supplier->id ?? 'undefined';
        \Log::info('We was trying to handle product that was not present in DB. ID=' . $item['id'] . '. Supplier ID=' . $supId);

        return;

        $product = new Product();

//        $product->id = $item['id'];
        if (!$item['data_postupleniya']) {
            $item['data_postupleniya'] = null;
        } elseif ($item['data_postupleniya'] === '\'') {
            $item['data_postupleniya'] = null;
        } else {
            $item['data_postupleniya'] = date('Y-m-d', strtotime($item['data_postupleniya']));
        }


        $product->id_from_supplier = $item['id_u_postavshchika'];
        $product->onOrder = $item['pod_zakaz'];
        $product->quantity = $item['v_nalichii'];
        $product->receiptDate = $item['data_postupleniya'];
        $product->production_time = $item['srok_izgotovleniya'];

        $product->save();

        $product->prices()->create(
            [
                'item_type' => 'product',
                'item_id' => $product->id,
                'currency_code' => 'RUB',
                'price_type_id' => SupplierService::OLD_PRICE,
                'value' => $item['staraya_tsena'],
            ]
        );
        $product->prices()->create(
            [
                'item_type' => 'product',
                'item_id' => $product->id,
                'currency_code' => 'RUB',
                'price_type_id' => SupplierService::SELLING_PRICE,
                'value' => $item['tsena_prodazhi'],
            ]
        );
        $product->prices()->create(
            [
                'item_type' => 'product',
                'item_id' => $product->id,
                'currency_code' => 'RUB',
                'price_type_id' => SupplierService::DISCOUNTING_PRICE,
                'value' => $item['aktsionnaya_tsena'],
            ]
        );
        $product->prices()->create(
            [
                'item_type' => 'product',
                'item_id' => $product->id,
                'currency_code' => 'RUB',
                'price_type_id' => SupplierService::PURCHASE_PRICE,
                'value' => $item['zakupochnaya_tsena'],
            ]
        );

        if ($supplier) {
            ProductSupplier::query()->create([
                'product_id' => $product->id,
                'supplier_id' => $supplier->id
            ]);
        }
    }

    public function updateProduct(Product $product, array $item, ?Supplier $supplier)
    {
        if (!$item['data_postupleniya']) {
            $item['data_postupleniya'] = null;
        } elseif ($item['data_postupleniya'] === '\'' || $item['data_postupleniya'] === '\'') {
            $item['data_postupleniya'] = null;
        } else {
            $item['data_postupleniya'] = date('Y-m-d', strtotime($item['data_postupleniya']));
        }

        if (!$item['srok_izgotovleniya']) {
            $item['srok_izgotovleniya'] = null;
        } elseif ($item['srok_izgotovleniya'] === '\'') {
            $item['srok_izgotovleniya'] = null;
        }

        $product->update([
            'id_from_supplier' => $item['id_u_postavshchika'] ?? $product->id_from_supplier,
            'onOrder' => $item['pod_zakaz'] ?? $product->onOrder,
            'quantity' => $item['v_nalichii'] ?? $product->quantity,
            'receiptDate' => $item['data_postupleniya'] ?? $product->receiptDate,
            'production_time' => $item['srok_izgotovleniya'] ?? $product->production_time
        ]);

        if ($product->prices()->where('price_type_id', SupplierService::OLD_PRICE)->first() &&
            $item['staraya_tsena']) {
            $product->prices()->where('price_type_id', SupplierService::OLD_PRICE)
                ->update(['value' => $item['staraya_tsena'] * 100]);
        } elseif (!$product->prices()->where('price_type_id', SupplierService::OLD_PRICE)->first() &&
            $item['staraya_tsena']) {
            $product->prices()->create(
                [
                    'item_type' => 'product',
                    'item_id' => $product->id,
                    'currency_code' => 'RUB',
                    'price_type_id' => SupplierService::OLD_PRICE,
                    'value' => $item['staraya_tsena'],
                ]
            );
        }

        if ($product->prices()->where('price_type_id', SupplierService::SELLING_PRICE)->first() &&
            $item['tsena_prodazhi']) {
            $product->prices()->where('price_type_id', SupplierService::SELLING_PRICE)
                ->update(['value' => $item['tsena_prodazhi'] * 100]);
        } elseif (!$product->prices()->where('price_type_id', SupplierService::SELLING_PRICE)->first() &&
            $item['tsena_prodazhi']) {
            $product->prices()->create(
                [
                    'item_type' => 'product',
                    'item_id' => $product->id,
                    'currency_code' => 'RUB',
                    'price_type_id' => SupplierService::SELLING_PRICE,
                    'value' => $item['tsena_prodazhi'],
                ]
            );
        }

        if ($product->prices()->where('price_type_id', SupplierService::DISCOUNTING_PRICE)->first() &&
            $item['aktsionnaya_tsena']) {
            $product->prices()->where('price_type_id', SupplierService::DISCOUNTING_PRICE)
                ->update(['value' => $item['aktsionnaya_tsena'] * 100]);
        } elseif (!$product->prices()->where('price_type_id', SupplierService::DISCOUNTING_PRICE)->first()
            && $item['aktsionnaya_tsena']) {
            $product->prices()->create(
                [
                    'item_type' => 'product',
                    'item_id' => $product->id,
                    'currency_code' => 'RUB',
                    'price_type_id' => SupplierService::DISCOUNTING_PRICE,
                    'value' => $item['aktsionnaya_tsena'],
                ]
            );
        }

        if ($product->prices()->where('price_type_id', SupplierService::PURCHASE_PRICE)->first() &&
            $item['zakupochnaya_tsena']) {
            $product->prices()->where('price_type_id', SupplierService::PURCHASE_PRICE)
                ->update(['value' => $item['zakupochnaya_tsena'] * 100]);
        } elseif (!$product->prices()->where('price_type_id', SupplierService::PURCHASE_PRICE)->first() &&
            $item['zakupochnaya_tsena']) {
            $product->prices()->create(
                [
                    'item_type' => 'product',
                    'item_id' => $product->id,
                    'currency_code' => 'RUB',
                    'price_type_id' => SupplierService::PURCHASE_PRICE,
                    'value' => $item['zakupochnaya_tsena'],
                ]
            );
        }

        if (is_null($product->suppliers()->first()) && $supplier) {
            ProductSupplier::query()->create([
                'product_id' => $product->id,
                'supplier_id' => $supplier->id
            ]);
        } elseif($supplier) {
            $product->suppliers()->first()->update([
                'product_id' => $product->id,
                'supplier_id' => $supplier->id
            ]);
        }
    }
}
