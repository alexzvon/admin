<?php namespace App\Http\Controllers\Api\Shop;

use App\Models\Shop\Product\ProductVideo;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class ProductVideosController extends BaseController
{
    public function getByProductId($product_id)
    {
        return ProductVideo::where('product_id', $product_id)
          ->get();
    }

    public function add($product_id)
    {
        $providerId = request('provider_id');

        ProductVideo::create([
          'product_id'  => $product_id,
          'provider_id' => $providerId,
          'video_id'    => ProductVideo::getIdFromUrl(request('url'), $providerId)
        ]);

        return $this->getByProductId($product_id);
    }

    public function update($product_id)
    {
        ProductVideo::where('id', request('id'))
          ->update([
            'title' => request('title'),
            'visible' => request('visible')
          ]);

        return $this->getByProductId($product_id);
    }

    public function delete($product_id)
    {
        ProductVideo::where('id', request('id'))->delete();

        return $this->getByProductId($product_id);
    }

    /**
     * @return JsonResponse
     */
    public function getVimeoThumb(): JsonResponse
    {
        $thumb = '';

        try {
            $id = request('video_id');
            $arrVimeo = unserialize(file_get_contents("https://vimeo.com/api/v2/video/$id.php"));
            $thumb = $arrVimeo[0]['thumbnail_large']; // returns large thumbnail
        } catch (\Throwable $exception) {
            \Log::alert($exception->getMessage());
            \Log::alert($exception->getTraceAsString());
        }

        return response()->json(['url' => $thumb]);
    }
}
