<?php namespace App\Models\Shop\Contents;

use Illuminate\Database\Eloquent\Model;

class ContentHumanName extends Model
{
    protected $table = 'shop_content_human_names';

    protected $guarded = [
      'id',
    ];

    public $timestamps = false;


}