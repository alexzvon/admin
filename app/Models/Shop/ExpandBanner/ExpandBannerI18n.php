<?php namespace App\Models\Shop\ExpandBanner;

use Illuminate\Database\Eloquent\Model;

class ExpandBannerI18n extends Model
{

    protected $table = 'shop_expand_banners_i18n';

    protected $guarded = [];

    protected $casts = [];

    protected $fillable = [
      'bottom_small_text',
      'button_text',
      'expand_banner_id',
      'input_placeholder',
      'language_code',
      'offer',
      'title',
    ];


}