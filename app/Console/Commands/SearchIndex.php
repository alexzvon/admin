<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Shop\Product\Product;
use App\Models\City;
use Carbon\Carbon;

class SearchIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shop:search-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Индексирование товара для поиска';

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
     * @return mixed
     */
    public function handle()
    {
        $this->productsIndex();
        $this->citiesIndex();
    }

    protected function productsIndex()
    {
        $products = Product::with(
            // ['attributeOptions' => function($query) {
            //     $query->with('i18n');
            // }],
            ['categories' => function($query) {
                $query->with('i18n');
            }],
            ['styles' => function($query) {
                $query->with('i18n');
            }],
            ['rooms' => function($query) {
                $query->with('i18n');
            }],
            'i18n'
        )
            ->orderBy('indexed_at', 'desc')
            //->whereNull('indexed_at')
            // ->orWhere('indexed_at', '<', Carbon::now()->subDay()->toDateTimeString())
            ->take(20)
            //->inRandomOrder()
            ->get();

        $products->each(function($product) {
            $product->indexed_at = Carbon::now()->toDateTimeString();

            $product->save();
        });
    }

    protected function citiesIndex()
    {
        $cities = City::with('region')
            ->whereNull('indexed_at')
            ->orWhere('indexed_at', '<', Carbon::now()->subDay()->toDateTimeString())
            ->take(10)
            ->inRandomOrder()
            ->get();

        $cities->each(function($city) {
            $city->indexed_at = Carbon::now()->toDateTimeString();

            $city->save();
        });
    }
}
