<?php
declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Support\Collection;

use App\Models\Region as Model;

class RegionRepository extends CoreRepository
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
     * @param $query
     * @return Collection
     */
    public function search($query): Collection
    {
        return $this->startConditions()
            ->where('name', 'ILIKE', $query . '%')
            ->orderBy('name')
            ->get();
    }

    /**
     * @return Collection
     */
    public function getListRegionsTop(): Collection
    {
        return $this->startConditions()
            ->with(['countryI18n'])
            ->where('parent_id', '=', '0')
            ->orderBy('name')
            ->get();
    }
}