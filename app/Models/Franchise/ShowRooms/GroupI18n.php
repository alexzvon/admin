<?php

namespace App\Models\Franchise\ShowRooms;

use Illuminate\Database\Eloquent\Model;

class GroupI18n extends Model
{
    protected $table = 'franchise_showroom_group_i18n';

    protected $fillable = ['group_id', 'language_code', 'name', 'name_vin'];

    protected $primaryKey = 'group_id';
}
