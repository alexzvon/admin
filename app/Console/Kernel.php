<?php

namespace App\Console;

use App\Console\Commands\QuadCRM;
use App\Http\Controllers\Shop\QuadCRMTimesController;
use App\Services\Shop\QuadCRMTimesService;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\App;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        QuadCRM::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        try {
            $quadCRMTimesService = App::make(QuadCRMTimesService::class);
            $timeForExport = isset($quadCRMTimesService->findExportTime()->time) ?
                $quadCRMTimesService->findExportTime()->time : "00:00:00";
            $timeForImport = isset($quadCRMTimesService->findImportTime()->time) ?
                $quadCRMTimesService->findImportTime()->time : "00:00:00";

            $schedule->command('quadCRM:saveExport')->at(date('H:i', strtotime($timeForExport)));
            $schedule->command('quadCRM:saveImport')->at(date('H:i', strtotime($timeForImport)));
        } catch (\Throwable $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
        }

        $schedule->command('shop:product-counts')->dailyAt('04:00');
        $schedule->command('shop:search-index')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
