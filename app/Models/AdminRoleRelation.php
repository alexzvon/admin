<?php

namespace App\Models;

use MosseboShopCore\Models\Base\BaseModel;

class AdminRoleRelation extends BaseModel
{
    protected $tableKey = 'AdminRoleRelations';
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'admin_id',
        'admin_role_id'
    ];

    public $timestamps = false;

    public function admin()
    {
        return $this->hasOne(Admin::class, 'admin_id');
    }

    public function role()
    {
        return $this->hasOne(AdminRole::class, 'admin_role_id');
    }
}
