<?php
declare(strict_types=1);

namespace App\Repositories\Product;

use App\Repositories\CoreRepository;
use App\Models\Shop\Product\Product as Model;
use Illuminate\Support\Collection;

class ProductRepository extends CoreRepository
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
    public function getAllIdsProducts(): Collection
    {
        return $this->startConditions()
            ->select('id')
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->get();
    }


}