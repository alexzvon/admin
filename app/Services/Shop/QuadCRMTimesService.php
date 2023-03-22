<?php

namespace App\Services\Shop;

use App\Contracts\Repositories\Shop\QuadCRMTimesRepositoryInterface;
use App\Models\Admin;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use App\Models\Shop\QuadCRMTimes\QuadCRMTimes;

/**
 * Class QuadCRMTimesService
 * @package App\Services\Shop
 */
class QuadCRMTimesService
{
    const TIME_FOR_EXPORT = 'time-for-export';
    const TIME_FOR_IMPORT = 'time-for-import';

    /**
     * @var QuadCRMTimesRepositoryInterface
     */
    protected $quadCRMTimesRepositoryInterface;

    /**
     * QuadCRMTimesService constructor.
     * @param QuadCRMTimesRepositoryInterface $quadCRMTimesRepositoryInterface
     */
    public function __construct(QuadCRMTimesRepositoryInterface $quadCRMTimesRepositoryInterface)
    {
        $this->quadCRMTimesRepositoryInterface = $quadCRMTimesRepositoryInterface;
    }

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByFilters(int $perPage): LengthAwarePaginator
    {
        return $this->quadCRMTimesRepositoryInterface->getByFilters($perPage);
    }

    /**
     * @return QuadCRMTimes|null
     */
    public function findExportTime(): ?QuadCRMTimes
    {
        return $this->quadCRMTimesRepositoryInterface->findExportTime();
    }

    /**
     * @return QuadCRMTimes|null
     */
    public function findImportTime(): ?QuadCRMTimes
    {
        return $this->quadCRMTimesRepositoryInterface->findImportTime();
    }

    /**
     * @param QuadCRMTimes $quadCRMTime
     * @param string $time
     * @return bool
     */
    public function update(QuadCRMTimes $quadCRMTime, string $time): bool
    {
        return $quadCRMTime->update([
            'admin_id' => Auth::guard('admin.web')->user()->id,
            'time' => $time,
        ]);
    }

    /**
     * @return bool
     */
    public function checkAdminRolePermission(): bool
    {
        /**
         * @var Admin $user
         */
        $user = auth('admin.web')->user();
        if (!$user->hasPermission('quad-crm.all')) {
            abort(403);
        }

        return true;
    }
}
