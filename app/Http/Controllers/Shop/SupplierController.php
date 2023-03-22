<?php

namespace App\Http\Controllers\Shop;

use App\Http\Requests\Shop\Supplier\AddRequest;
use App\Http\Requests\Shop\Supplier\DeleteRequest;
use App\Http\Requests\Shop\Supplier\GetRequest;
use App\Models\Admin;
use App\Services\Shop\SupplierService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupplierController extends Controller
{
    const DEFAULT_PER_PAGE = 8;

    /**
     * @var SupplierService
     */
    private $supplierService;

    /**
     * SupplierController constructor.
     * @param SupplierService $supplierService
     */
    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    /**
     * @param GetRequest $request
     * @return Factory|Application|View
     */
    public function get(GetRequest $request)
    {
        $this->checkAdminRolePermission();

        $suppliers = $this->supplierService->getByFilters
        (
            $request->getPerPage() ?? self::DEFAULT_PER_PAGE
        );

        return view('supplier.suppliers', compact('suppliers'));
    }

    /**
     * @param AddRequest $request
     * @return RedirectResponse
     */
    public function add(AddRequest $request): RedirectResponse
    {
        $this->checkAdminRolePermission();

        $this->supplierService->addToQuadCRM($request->getId());

        return redirect()->back();
    }

    /**
     * @param DeleteRequest $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function delete(DeleteRequest $request): RedirectResponse
    {
        $this->checkAdminRolePermission();

        $this->supplierService->deleteFromQuadCRM($request->getId());

        return redirect()->back();
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
