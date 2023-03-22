<?php

namespace App\Models\Shop\Supplier;

use Illuminate\Database\Eloquent\Model;

class QuadCRMSupplier extends Model
{
    protected $table = 'quad_crm_suppliers';

    protected $fillable = [
        'supplier_id',
        'status',
    ];
}
