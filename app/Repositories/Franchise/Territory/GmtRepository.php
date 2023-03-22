<?php
declare(strict_types=1);

namespace App\Repositories\Franchise\Territory;

use \Illuminate\Database\Eloquent\Collection;
use App\Repositories\CoreRepository;
use App\Models\Franchise\Territory\Gmt as Model;

class GmtRepository extends CoreRepository
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
     * @return mixed
     */
    public function getListGmt(): Collection
    {
        return $this->startConditions()->get();
    }

}