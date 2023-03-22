<?php

namespace App\Models\Shop\QuickFilter;

use Illuminate\Database\Eloquent\Model;

class QuickFilterI18n extends Model
{
    protected $table = 'shop_quick_filter_i18n';

    protected $fillable = [
        'quick_filter_id',
        'language_code',
        'title',
        'displayed_name',
    ];

    protected $primaryKey = 'quick_filter_id';

}
