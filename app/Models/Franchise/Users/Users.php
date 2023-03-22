<?php

namespace App\Models\Franchise\Users;

use Illuminate\Database\Eloquent\Model;
use App\Models\Franchise\Companies\Companies;
use Illuminate\Support\Facades\Hash;

class Users extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'password',
        'is_franchisee',
        'is_designer',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'email_verified_at',
    ];

    public function franchise_companies() {
        return $this->hasOne(Companies::class, 'user_id', 'id');
    }
}
