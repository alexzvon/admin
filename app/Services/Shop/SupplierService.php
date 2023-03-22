<?php

namespace App\Services\Shop;

use App\Contracts\Repositories\Shop\SupplierRepositoryInterface;
use App\Imports\QuadCRMImport;
use App\Models\Shop\Product\Product;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use App\Models\Shop\Supplier\QuadCRMSupplier;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class SupplierService
 * @package App\Services\Shop
 */
class SupplierService
{
    const OLD_PRICE = 1;
    const SELLING_PRICE = 7;
    const DISCOUNTING_PRICE = 6;
    const PURCHASE_PRICE = 4;
    /**
     * @var SupplierRepositoryInterface
     */
    protected $supplierRepository;

    /**
     * SupplierService constructor.
     * @param SupplierRepositoryInterface $supplierRepository
     */
    public function __construct(SupplierRepositoryInterface $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByFilters(int $perPage): LengthAwarePaginator
    {
        return $this->supplierRepository->getByFilters($perPage);
    }

    /**
     * @return \Illuminate\Support\Collection|null
     */
    public function getAllIds(): ?\Illuminate\Support\Collection
    {
        return $this->supplierRepository->getAllIds();
    }

    /**
     * @param $id
     * @return QuadCrmSupplier
     */
    public function findOrFailById($id): QuadCrmSupplier
    {
        $quadCrmSupplier = $this->supplierRepository->findById($id);

        if (!$quadCrmSupplier) {
            throw new InvalidArgumentException('Supplier does not assigned!');
        }

        return $quadCrmSupplier;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function addToQuadCRM(int $id)
    {
        return QuadCRMSupplier::updateOrCreate(
            ['supplier_id' => $id],
            ['supplier_id' => $id, 'status' => true]
        );
    }

    /**
     * @param int $id
     * @return false|int
     * @throws Exception
     */
    public function deleteFromQuadCRM(int $id)
    {
        $quadCrmSupplier = $this->findOrFailById($id);

        if ($quadCrmSupplier->delete()) {
            return Response::HTTP_OK;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getSupplierProducts(): array
    {
        $supplierIds = $this->getAllIds();

        if (count($supplierIds)) {
            return $this->supplierRepository->getProductsBySuppliersId($supplierIds->toArray());
        }

        return $this->supplierRepository->getJustAllProducts();
    }

    /**
     * @param $product
     * @return mixed
     */
    public function updateOrCreateProduct($product)
    {
        return $this->supplierRepository->updateOrCreateProduct($product);
    }

    /**
     * @param $file
     * @throws Exception
     */
    public function importFromCommand($file)
    {
        $data = Excel::toArray(new QuadCRMImport(App::make(SupplierService::class)), $file)[0];
        $this->checkValidExcel($data);
        foreach ($data as $item) {
//            if ($this->checkArrayValues($item)) {
                $this->updateOrCreateProduct($item);
//            }
        }
    }

    /**
     * @param array $data
     * @throws Exception
     */
    public function checkValidExcel(array $data)
    {
        if (!isset($data[0]) || !array_key_exists('id', $data[0]) || !array_key_exists('id_u_postavshchika', $data[0]) ||
            !array_key_exists('postavshchiki', $data[0]) || !array_key_exists('staraya_tsena', $data[0]) ||
            !array_key_exists('tsena_prodazhi', $data[0]) || !array_key_exists('aktsionnaya_tsena', $data[0]) ||
            !array_key_exists('zakupochnaya_tsena', $data[0]) || !array_key_exists('pod_zakaz', $data[0]) ||
            !array_key_exists('v_nalichii', $data[0]) || !array_key_exists('data_postupleniya', $data[0]) ||
            !array_key_exists('srok_izgotovleniya', $data[0])) {
              throw new Exception("Your excel file's data is not supported by requirements!");
        } else {
            \Log::debug('excel is ok');
        }
    }

    /**
     * @param array $item
     * @return bool
     */
    public function checkArrayValues(array $item): bool
    {
        if (!value($item['id']) || !value($item['id_u_postavshchika']) || !value($item['postavshchiki']) ||
            !value($item['staraya_tsena']) || !value($item['tsena_prodazhi']) || !value($item['aktsionnaya_tsena']) ||
            !value($item['zakupochnaya_tsena']) || !value($item['pod_zakaz']) || !value($item['v_nalichii']) ||
            !value($item['data_postupleniya']) || !value($item['srok_izgotovleniya'])) {

            return false;
        }

        return true;
    }
}
