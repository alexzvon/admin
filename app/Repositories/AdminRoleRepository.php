<?php

namespace App\Repositories;

use MosseboShopCore\Repositories\Base\BaseRepository;
use App\Contracts\Repositories\AdminRoleRepository as AdminRoleRepositoryContract;
use Illuminate\Support\Collection;

class AdminRoleRepository extends BaseRepository implements AdminRoleRepositoryContract
{
    protected $model = \App\Models\AdminRole::class;

    public function getCollectionRaw(): Collection
    {
        return $this->model::orderBy('position', 'asc')->get();
    }
}