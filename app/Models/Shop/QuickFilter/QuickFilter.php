<?php

namespace App\Models\Shop\QuickFilter;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
//use Spatie\MediaLibrary\Models\Media;

use App\Models\Shop\Category\Category;

class QuickFilter extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $table = 'shop_quick_filter';

    protected $fillable = ['id', 'category_id', 'adress', 'status', 'slug',];

    public function i18n()
    {
        return $this->hasMany(QuickFilterI18n::class, 'quick_filter_id', 'id');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
