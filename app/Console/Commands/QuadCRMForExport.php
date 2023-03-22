<?php

namespace App\Console\Commands;

use App\Exports\QuadCRMExport;
use App\Services\Shop\SupplierService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;

class QuadCRMForExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quadCRM:saveExport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command QuadCRMExport';

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
