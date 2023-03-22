<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\Product\OtherProductOptionsService;

class ConversionOtherPropductOptions extends Command
{
    protected $otherProductOptionsService;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conversion:other_product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Конвертация в другие варианты товара';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->otherProductOptionsService = app(OtherProductOptionsService::class);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start = microtime(true);

        dump('start conversion');

        $this->otherProductOptionsService->conversion();

        $end = microtime(true);

        dump('start: ' . $start);
        dump('end: ' . $end);
        dump('time: ' . ($end - $start));
        dd('end conversion');
    }
}
