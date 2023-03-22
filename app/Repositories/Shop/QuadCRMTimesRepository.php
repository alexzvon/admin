<?php

namespace App\Repositories\Shop;

use App\Contracts\Repositories\Shop\QuadCRMTimesRepositoryInterface;
use App\Models\Shop\QuadCRMTimes\QuadCRMTimes;
use App\Models\Shop\Supplier\QuadCRMSupplier;
use App\Repositories\Repository;
use App\Services\Shop\QuadCRMTimesService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class QuadCRMTimesRepository
 * @package App\Repositories\Shop
 */
class QuadCRMTimesRepository extends Repository implements QuadCRMTimesRepositoryInterface
{
    /**
     * @return string
     */
    protected function getModelClassName(): string
    {
        return QuadCRMTimes::class;
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
     * @return QuadCRMTimes|null
     */
    public function findExportTime(): ?QuadCRMTimes
    {
        /**
         * @var QuadCRMTimes|null $timeForExport
         */
        $timeForExport = $this->model()->where('status', QuadCRMTimesService::TIME_FOR_EXPORT)->first();

        return $timeForExport;
    }

    /**
     * @return QuadCRMTimes|null
     */
    public function findImportTime(): ?QuadCRMTimes
    {
        /**
         * @var QuadCRMTimes|null $timeForExport
         */
        $timeForExport = $this->model()->where('status', QuadCRMTimesService::TIME_FOR_IMPORT)->first();

        return $timeForExport;
    }
}
