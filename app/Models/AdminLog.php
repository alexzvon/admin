<?php

namespace App\Models;

use MosseboShopCore\Models\Base\BaseModel;

class AdminLog extends BaseModel
{
    protected $tableKey = 'AdminLogs';

    protected $fillable = [
        'admin_id',
        'type',
        'message',
    ];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'admin_id');
    }
}
