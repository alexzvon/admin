<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\City as Model;
use Illuminate\Support\Collection;

class CityRepository extends CoreRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getModelClass(): string
    {
        return Model::class;
    }

    public function search($query): Collection
    {
        return $this->startConditions()
            ->with(['countryI18n', 'region', 'region.district'])
            ->where('name', 'ILIKE', $query . '%')
            ->orderBy('name')
            ->get();
    }
}