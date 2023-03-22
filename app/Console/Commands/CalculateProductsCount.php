<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Shop\Product\ProductCount;
use App\Models\Shop\Product\Product;
use Categories;
use Styles;
use Rooms;

class CalculateProductsCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shop:product-counts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Подсчет количества товаров в категориях, стилях и комнатах';

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
        ProductCount::truncate();

        $this->calculateCatalogCounts();
        $this->calculateStyleCounts();
        $this->calculateStyleCatalogCounts();
        $this->calculateRoomCounts();
        $this->calculateRoomCatalogCounts();
    }

    protected function calculateCatalogCounts()
    {
        Categories::getCollection()->each(function ($category) {
            (new ProductCount([
                'category_id' => $category->id,
                'count' => Product::enabled()
                    ->whereCategory($this->getCategoryIds($category))
                    ->count(),
            ]))->save();
        });
    }

    protected function calculateStyleCounts()
    {
        Styles::getCollection()->each(function ($style) {
            (new ProductCount([
                'style_id' => $style->id,
                'count' => Product::enabled()
                    ->whereStyle($style->id)
                    ->count(),
            ]))->save();
        });
    }

    protected function calculateStyleCatalogCounts()
    {
        Styles::getCollection()->each(function ($style) {
            Categories::getCollection()->each(function ($category) use($style) {
                (new ProductCount([
                    'category_id' => $category->id,
                    'style_id' => $style->id,
                    'count' => Product::enabled()
                        ->whereCategory($this->getCategoryIds($category))
                        ->whereStyle($style->id)
                        ->count(),
                ]))->save();
            });
        });
    }

    protected function calculateRoomCounts()
    {
        Rooms::getCollection()->each(function ($room) {
            (new ProductCount([
                'room_id' => $room->id,
                'count' => Product::enabled()->whereRoom($room->id)->count(),
            ]))->save();
        });
    }

    protected function calculateRoomCatalogCounts()
    {
        Rooms::getCollection()->each(function ($room) {
            Categories::getCollection()->each(function ($category) use($room) {
                (new ProductCount([
                    'category_id' => $category->id,
                    'room_id' => $room->id,
                    'count' => Product::enabled()
                        ->whereCategory($this->getCategoryIds($category))
                        ->whereRoom($room->id)
                        ->count(),
                ]))->save();
            });
        });
    }

    protected function getCategoryIds($category)
    {
        $categories = $category->descendants()->pluck('id');
        $categories->push($category->id);

        return $categories->toArray();
    }
}
