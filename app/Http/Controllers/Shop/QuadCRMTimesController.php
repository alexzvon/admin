<?php

namespace App\Http\Controllers\Shop;

use App\Http\Requests\Shop\QuadCRMTime\EditRequest;
use App\Http\Requests\Shop\QuadCRMTime\GetRequest;
use App\Http\Requests\Shop\QuadCRMTime\UpdateRequest;
use App\Models\Admin;
use App\Models\Shop\QuadCRMTimes\QuadCRMTimes;
use App\Services\Shop\QuadCRMTimesService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class QuadCRMTimesController extends Controller
{
    /**
     * @var QuadCRMTimesService
     */
    private $quadCRMTimesService;

    /**
     * SupplierController constructor.
     * @param QuadCRMTimesService $quadCRMTimesService
     */
    public function __construct(QuadCRMTimesService $quadCRMTimesService)
    {
        $this->quadCRMTimesService = $quadCRMTimesService;
    }

    /**
     * @return QuadCRMTimes|null
     */
    public function findExportTime(): ?QuadCRMTimes
    {
        $this->checkAdminRolePermission();

        return $this->quadCRMTimesService->findExportTime();
    }

    /**
     * @return QuadCRMTimes|null
     */
    public function findImportTime(): ?QuadCRMTimes
    {
        $this->checkAdminRolePermission();

        return $this->quadCRMTimesService->findImportTime();
    }

    /**
     * @param GetRequest $request
     * @return Factory|Application|View
     */
    public function getExportTime(GetRequest $request)
    {
        $this->checkAdminRolePermission();

        $exportTime = $this->findExportTime();

        return view('quadCRMTimes.timeForExport.index', compact('exportTime'));
    }

    /**
     * @param EditRequest $request
     * @return Factory|Application|View
     */
    public function editExportTime(EditRequest $request)
    {
        $this->checkAdminRolePermission();

        $exportTime = $this->findExportTime();

        return view('quadCRMTimes.timeForExport.edit', compact('exportTime'));
    }

    /**
     * @param UpdateRequest $request
     * @return Factory|Application|View
     */
    public function updateExportTime(UpdateRequest $request)
    {
        $this->checkAdminRolePermission();

        $this->quadCRMTimesService->update($this->findExportTime(), $request->getTime());
        $exportTime = $this->findExportTime();

        return view('quadCRMTimes.timeForExport.index', compact('exportTime'));
    }

    public function getImportTime(GetRequest $request)
    {
        $this->checkAdminRolePermission();

        $importTime = $this->findImportTime();

        return view('quadCRMTimes.timeForImport.index', compact('importTime'));
    }

    /**
     * @param EditRequest $request
     * @return Factory|Application|View
     */
    public function editImportTime(EditRequest $request)
    {
        $this->checkAdminRolePermission();

        $importTime = $this->findImportTime();

        return view('quadCRMTimes.timeForImport.edit', compact('importTime'));
    }

    /**
     * @param UpdateRequest $request
     * @return Factory|Application|View
     */
    public function updateImportTime(UpdateRequest $request)
    {
        $this->checkAdminRolePermission();

        $this->quadCRMTimesService->update($this->findImportTime(), $request->getTime());
        $importTime = $this->findImportTime();

        return view('quadCRMTimes.timeForImport.index', compact('importTime'));
    }

    /**
     * @return void
     */
    private function checkAdminRolePermission(): void
    {
        /**
         * @var Admin $user
         */
        $user = auth('admin.web')->user();
        if (!$user->hasPermission('quad-crm.all')) {
            abort(403);
        }
    }
}
