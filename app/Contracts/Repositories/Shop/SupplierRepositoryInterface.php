<?php

namespace App\Contracts\Repositories\Shop;

use App\Contracts\Repositories\RepositoryInterface;
use App\Models\Shop\Product\Product;
use App\Models\Shop\Supplier\QuadCrmSupplier;
use App\Models\Shop\Supplier\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface SupplierRepositoryInterface
 * @package App\Contracts\Repositories\Shop
 */
interface SupplierRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByFilters(int $perPage): LengthAwarePaginator;

    /**
     * @return \Illuminate\Support\Collection|null
     */
    public function getAllIds(): ?\Illuminate\Support\Collection;

    /**
     * @return array
     */
    public function getJustAllProducts(): array;

    /**
     * @param int $id
     * @return QuadCrmSupplier|null
     */
    public function findById(int $id): ?QuadCrmSupplier;

    /**
     * @param array|null $suppliersId
     * @return array
     */
    public function getProductsBySuppliersId(?array $suppliersId): array;

    public function updateOrCreateProduct(array $item);

    public function createProduct(array $item, ?Supplier $supplier);

    public function updateProduct(Product $product, array $item, ?Supplier $supplier);
}
