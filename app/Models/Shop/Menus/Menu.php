<?php namespace App\Models\Shop\Menus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $table = 'shop_menus';

    protected $guarded = [
      'id',
    ];

    protected $fillable = [
      'name',
      'url',
      'parent_menu_id',
      'position',
      'enabled',
      'created_at',
      'updated_at'
    ];

    protected $casts = [];
    protected $appends = [];

}