<?php namespace App\Models\Shop\ExpandBanner;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Exception\NotReadableException;
use App\Services\Image\Facades\Image;

class ExpandBanner extends Model
{
    use SoftDeletes;

    protected $table = 'shop_expand_banners';

    protected $guarded = [
      'id',
    ];

    protected $fillable = [
      'name',
      'enabled',
      'delay_seconds',
      'image',
      'created_at',
      'updated_at'
    ];

    protected $casts = [];

    protected $with = [
      'i18n',
    ];

    protected $appends = [
      'src'
    ];


    public function i18n()
    {
        return $this
          ->belongsTo(ExpandBannerI18n::class, 'id', 'expand_banner_id');
    }


    public function getSrcAttribute()
    {
        return 'https://143824.selcdn.ru/cdn.candellabra.com/i/expands/'.$this->attributes['id'].'-orig.jpg';
    }


    public function uploadImage($file)
    {
        try {
            $path = Image::open($file)
              ->resize(600, 600)
              ->save()
              ->getPath();
        } catch (NotReadableException $ex) {
            $path = Image::openFromUrl($file)
              ->resize(600, 600)
              ->save()
              ->getPath();
        }

        Storage::disk('cloud')
          ->put('i/expands/' . $this->attributes['id'] . '-orig.jpg', file_get_contents($path));
    }



}