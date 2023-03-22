<?php
declare(strict_types=1);

namespace App\Repositories\Franchise;

use App\Repositories\CoreRepository;
use App\Models\User as Model;
use Illuminate\Support\Collection;

class UsersRepository extends CoreRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getModelClass(): string
    {
        return Model::class;
    }

    /**
     * @return Collection
     */
    public function getListUsers(): Collection
    {
        return $this->startConditions()
            ->select(['id', 'first_name', 'last_name', 'email'])
            ->get();
    }
}