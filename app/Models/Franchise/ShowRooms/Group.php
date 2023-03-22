<?php

namespace App\Models\Franchise\ShowRooms;

use Illuminate\Database\Eloquent\Model;

use App\Models\Region;

class Group extends Model
{
    protected $table = 'franchise_showroom_group';

    protected $fillable = ['id', 'region_id', 'status'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function i18n()
    {
        return $this->hasMany(GroupI18n::class, 'group_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function region()
    {
        return $this->hasOne(Region::class, 'id', 'region_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rooms()
    {
        return $this->hasMany(Room::class, 'group_id', 'id');
    }
}
