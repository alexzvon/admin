<?php namespace App\Http\Controllers;

use App\Http\Controllers\Exports\ExportProductImages;
use App\Http\Controllers\Exports\ExportProducts;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class ExportsController
 *
 * @package App\Http\Controllers
 */
class ExportsController extends Controller
{
    public function products()
    {
        $date = Carbon::now();
        $dateStr = $date->toDateTimeString();

        return (new ExportProducts)->download("products $dateStr.xlsx");
    }

    /**
     * @return BinaryFileResponse
     */
    public function images(): BinaryFileResponse
    {
        $date = Carbon::now();
        $dateStr = $date->toDateTimeString();

        return (new ExportProductImages())->download("product_imgs_ $dateStr.xlsx");
    }
}
