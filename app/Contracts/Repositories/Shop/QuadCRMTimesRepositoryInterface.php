<?php

namespace App\Contracts\Repositories\Shop;

use App\Contracts\Repositories\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Shop\QuadCRMTimes\QuadCRMTimes;
/**
 * Interface QuadCRMTimesRepositoryInterface
 * @package App\Contracts\Repositories\Shop
 */
interface QuadCRMTimesRepositoryInterface extends RepositoryInterface
{
    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByFilters(int $perPage): LengthAwarePaginator;

    /**
     * @return QuadCRMTimes|null
     */
    public function findExportTime(): ?QuadCRMTimes;

    /**
     * @return QuadCRMTimes|null
     */
    public function findImportTime(): ?QuadCRMTimes;
}
