<?php

namespace App\Console\Commands;

use App\Http\Controllers\Shop\QuadCRMController;
use App\Services\Shop\SupplierService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class QuadCRMForImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quadCRM:saveImport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        try {
            /** @var SupplierService $supplierService */
            $supplierService = App::make(SupplierService::class);
            $file = storage_path('app/public/fromqx/QuadDB_in.xlsx');
            $supplierService->importFromCommand($file);
        } catch (\Throwable $exception) {
            Log::warning('Something went wrong or QuadDB_in.xlsx does not exist!');
        }
    }
}
