<?php

namespace App\Console\Commands;

use App\Repositories\Shop\SupplierRepository;
use App\Services\Shop\SupplierService;
use Illuminate\Console\Command;
use App\Exports\QuadCRMExport;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class QuadCRM extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quadCRM:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command QuadCRM';

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
     *
     */
    public function handle()
    {
        Excel::store(new QuadCRMExport(App::make(SupplierService::class)),
            'fromqx/QuadDB_out.xlsx',
            'public'
        );

        Excel::store(new QuadCRMExport
        (
            App::make(SupplierService::class)),
            'fromqx/history/QuadDB_out-' .time() .'.xlsx',
            'public'
        );
    }
}
