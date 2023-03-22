<?php
declare(strict_types=1);

namespace App\Services\Product;

use App\Repositories\Product\OtherProductOptionsRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductI18nRepository;
use App\Repositories\Product\Attribute\LinkAttributeRelationRepository;

class OtherProductOptionsService
{
    protected $idProduct;
    protected $idAddProduct;
    protected $arrIdProduct;
    protected $arrIdChildren;
    protected $arrOtherProductOptions;

    protected $linkAttributeRelationRepository;
    protected $otherProductOptionsRepository;
    protected $productRepository;
    protected $productI18nRepository;

    protected $search;
    protected $limit;
    protected $offset;
    protected $page;

    public function __construct()
    {
        $this->linkAttributeRelationRepository = app(LinkAttributeRelationRepository::class);
        $this->otherProductOptionsRepository = app(OtherProductOptionsRepository::class);
        $this->productRepository = app(ProductRepository::class);
        $this->productI18nRepository = app(ProductI18nRepository::class);

        $this->arrIdProduct = [];
        $this->arrIdChildren = [];
        $this->arrOtherProductOptions = [];

        $this->idProduct = 0;
        $this->idAddPropduct = 0;
        $this->search = '';
        $this->limit = 0;
        $this->offset = 0;
        $this->page = 0;
    }


    public function getOtherProductOption($idProduct)
    {
        $this->idProduct = (int) $idProduct;

        return $this->otherProductOptionsRepository->getOtherProductOptions($this->idProduct);
    }

    /**
     * Добавить все связи
     * @param $input
     */
    public function addLinksOtherProductOptions($input)
    {
        $this->idProduct = (int) $input['product_id'];
        $this->idAddPropduct = (int) $input['add_product_id'];
        $count = 0;

        $this->getArrIdsOtherProductOptions();

        foreach ($this->arrOtherProductOptions as $idProduct) {
            $count = $this->createLinkParentChildren($idProduct, $this->idAddPropduct) ? $count++ : $count;
            $count = $this->createLinkParentChildren($this->idAddPropduct, $idProduct) ? $count++ : $count;
        }

        return ['success' => true, 'count' => $count];
    }

    /**
     * Удалить все всязи
     * @param $input
     */
    public function delLinksOtherProductOptions($input)
    {
        $this->idProduct = (int) $input['product_id'];
        $this->idAddPropduct = (int) $input['add_product_id'];
        $count = 0;

        $this->getArrIdsOtherProductOptions();

        foreach ($this->arrOtherProductOptions as $idProduct) {
            $count = $this->deleteLinkParentChildren($idProduct, $this->idAddPropduct) ? $count++ : $count;
            $count = $this->deleteLinkParentChildren($this->idAddPropduct, $idProduct) ? $count++ : $count;
        }

        return ['success' => true, 'count' => $count];
    }

    /**
     * Отобрать другие варианты товара для добавления
     * @param $input
     * @return array
     */
    public function getAddOtherProductOptions($input)
    {
        $this->idProduct = $input['product_id'] ?? 0;
        $this->search = preg_replace('/!!space!!/', ' ', $input['search'] ?? '');
        $this->limit = $input['limit'] ?? 0;
        $this->offset = $input['offset'] ?? 0;
        $this->page = $input['page'] ?? 0;

        $this->getIdChildren();

        $result = [
            'products' => $this->getAddProducts(),
            'page' => $this->page,
        ];

        return $result;
    }

    /**
     * Конвертация
     */
    public function conversion()
    {
        $this->getAllIdProduct();

        foreach($this->arrIdProduct as $product) {
            $linkedOptions = $this->linkAttributeRelationRepository->getLinkAttributeOptionsRelation('product', $product['id'], 114);

            if (!is_null($linkedOptions)) {
                foreach ($linkedOptions->linkedOptions as $options) {
                    if (0 < $product['id'] && 0 < $options->option_product_id) {
                        $this->createLinkParentChildren($product['id'], $options->option_product_id);
                    }
                }
            }
        }
    }

    /**
     * Загрузить продукты для добавления
     * @return mixed
     */
    protected function getAddProducts()
    {
        $not_in = $this->arrIdChildren;
        $not_in[] = $this->idProduct;

        return $this->productI18nRepository->getAddProducts(
            $this->search, $not_in, $this->limit, $this->offset
        );
    }

    /**
     * Загрузить другие варианты товара
     */
    protected function getIdChildren()
    {
        $items = $this->otherProductOptionsRepository->getIdsOtherProductOptions($this->idProduct);

        foreach ($items as $item) {
            $this->arrIdChildren[] = $item->children_id;
        }
    }

    /**
     * Загрузить id всех товаров
     */
    protected function getAllIdProduct()
    {
        $this->arrIdProduct = $this->productRepository->getAllIdsProducts()->toArray();
    }

    /**
     * Сoздание связки
     * @param $parent_id
     * @param $children_id
     * @param $other
     * @return bool
     */
    protected function createLinkParentChildren($parent_id, $children_id): bool
    {
        return $this->otherProductOptionsRepository->createParentChildren($parent_id, $children_id);
    }

    /**
     * Удаление связи
     * @param $parent_id
     * @param $children_id
     * @return bool
     */
    protected function deleteLinkParentChildren($parent_id, $children_id): bool
    {
        return $this->otherProductOptionsRepository->deleteParentChildren($parent_id, $children_id);
    }

    /**
     * Загрузить массив id для обработки
     */
    protected function getArrIdsOtherProductOptions()
    {
        $items = $this->otherProductOptionsRepository->getIdsOtherProductOptions($this->idProduct);

        $this->arrOtherProductOptions[] = $this->idProduct;

        foreach ($items as $item) {
            $this->arrOtherProductOptions[] = $item->children_id;
        }
    }
}