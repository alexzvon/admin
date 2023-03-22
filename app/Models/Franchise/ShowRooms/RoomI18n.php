<?php

namespace App\Models\Franchise\ShowRooms;

use Illuminate\Database\Eloquent\Model;

class RoomI18n extends Model
{
    protected $table = 'franchise_showroom_room_i18n';

    protected $fillable = [
        'room_id',
        'language_code',
        'name',
        'address',
        'work_time'
    ];

    protected $primaryKey = 'room_id';
}
