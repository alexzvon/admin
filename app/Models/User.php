<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Notifications\Notifiable;

use MosseboShopCore\Models\User as BaseUser;

use Cog\Contracts\Love\Liker\Models\Liker as LikerContract;
use Cog\Laravel\Love\Liker\Models\Traits\Liker;
use App\Models\Shop\Promo\PromoUse;

class User extends BaseUser implements LikerContract
{
    use Liker, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function promoCodeUses()
    {
        return $this->hasMany(PromoUse::class, $this->relationFieldName, 'id');
    }
}
