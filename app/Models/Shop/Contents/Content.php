<?php namespace App\Models\Shop\Contents;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $table = 'shop_contents';

    protected $guarded = [
      'id',
    ];

    protected $hidden = [
      'created_at',
      'updated_at',
    ];


}