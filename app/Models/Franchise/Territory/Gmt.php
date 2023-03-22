<?php

namespace App\Models\Franchise\Territory;

use Illuminate\Database\Eloquent\Model;

class Gmt extends Model
{
    protected $table = 'franchise_gmt';

    protected $fillable = [
        'id',
        'name',
        'hour',
    ];
}
