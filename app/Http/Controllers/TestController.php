<?php

namespace App\Http\Controllers;

use App\Services\Product\OtherProductOptionsService;

class TestController extends Controller
{
    protected $otherProductOptionsService;

    public function __construct()
    {
        $this->otherProductOptionsService = app(OtherProductOptionsService::class);
    }

    public function test()
    {
        $start = microtime(true);
        dump('test');

        $input['product_id'] = 103960;
        $input['add_product_id'] = 103966;

//        $this->otherProductOptionsService->addLinksOtherProductOptions($input);
//        $this->otherProductOptionsService->delLinksOtherProductOptions($input);

        $end = microtime(true);
        dump('start: ' . $start);
        dump('end: ' . $end);
        dump('time: ' . ($end - $start));
        dd('code test');
    }
}
