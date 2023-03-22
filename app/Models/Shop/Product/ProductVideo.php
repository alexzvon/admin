<?php namespace App\Models\Shop\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVideo extends Model
{
    use SoftDeletes;

    const PROVIDER_YOUTUBE = 1;
    const PROVIDER_VIMEO = 2;

    protected $table = "shop_product_videos";

    protected $guarded = [];

    protected $casts = [];


    public function product()
    {
        return $this
          ->hasOne(Product::class, 'id', 'product_id');
    }

    /**
     * @param string $url
     * @param int    $providerId
     *
     * @return mixed
     */
    public static function getIdFromUrl(string $url, int $providerId)
    {
        $match = null;
        switch ($providerId) {
            case ProductVideo::PROVIDER_YOUTUBE:
                return ProductVideo::getYoutubeIdFromUrl($url);
            case ProductVideo::PROVIDER_VIMEO:
                return ProductVideo::getVimeoIdFromUrl($url);
        }
    }

    /**
     * @param $url
     * @return mixed
     * // Here is a sample of the URLs this regex matches: (there can be more content after the given URL that will be ignored)
     * http://youtu.be/dQw4w9WgXcQ
     * http://www.youtube.com/embed/dQw4w9WgXcQ
     * http://www.youtube.com/watch?v=dQw4w9WgXcQ
     * http://www.youtube.com/?v=dQw4w9WgXcQ
     * http://www.youtube.com/v/dQw4w9WgXcQ
     * http://www.youtube.com/e/dQw4w9WgXcQ
     * http://www.youtube.com/user/username#p/u/11/dQw4w9WgXcQ
     * http://www.youtube.com/sandalsResorts#p/c/54B8C800269D7C1B/0/dQw4w9WgXcQ
     * http://www.youtube.com/watch?feature=player_embedded&v=dQw4w9WgXcQ
     * http://www.youtube.com/?feature=player_embedded&v=dQw4w9WgXcQ
     * It also works on the youtube-nocookie.com URL with the same above options.
     * It will also pull the ID from the URL in an embed code (both iframe and object tags)
     */
    public static function getYoutubeIdFromUrl($url)
    {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);

        return $match[1];
    }

    /**
     * @param $url
     *
     * @return mixed
     */
    public static function getVimeoIdFromUrl($url)
    {
        preg_match('%(http|https)?://(www\.|player\.)?vimeo\.com/(?:channels/(?:\w+/)?|groups/([^/]*)/videos/|video/|)(\d+)(?:|/\?)%i', $url, $match);

        return $match[4];
    }
}
