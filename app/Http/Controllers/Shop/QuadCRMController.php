<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\ImportFromQuadCRM\ImportRequest;
use App\Imports\QuadCRMImport;
use App\Models\Admin;
use App\Services\Shop\SupplierService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use InvalidArgumentException;
use Auth;

class QuadCRMController extends Controller
{
    public const AVAILABLE_FILE_MIMES = [
        'xlsx',
    ];

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

    public function createLink()
    {
        $this->checkAdminRolePermission();

        $link = action('Shop\QuadCRMController@saveAndDownloadQuadDBOut');

        Artisan::call('quadCRM:saveExport');

        return view('saveQuadCRM.index', compact('link'))->with('success', 'Successfully saved!');
    }

    /**
     * @return StreamedResponse
     */
    public function saveAndDownloadQuadDBOut(): StreamedResponse
    {
        $this->checkAdminRolePermission();

        return Storage::disk('public')->download('fromqx/QuadDB_out.xlsx');
    }

    /**
     * @return Factory|Application|View
     */
    public function importShow()
    {
        $this->checkAdminRolePermission();

        return view('importFromQuadCRM.index');
    }

    /**
     * @param ImportRequest $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function import(ImportRequest $request): RedirectResponse
    {
        $this->checkAdminRolePermission();

        $data = Excel::toArray(new QuadCRMImport(App::make(SupplierService::class)),$request->getExcelFile())[0];
        $this->supplierService->checkValidExcel($data);
        foreach ($data as $item) {
             $this->supplierService->updateOrCreateProduct($item);
        }

        return back()->with('message', 'Products were imported successfully!');
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
