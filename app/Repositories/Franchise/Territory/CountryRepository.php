<?php
declare(strict_types=1);

namespace App\Repositories\Franchise\Territory;

use \Illuminate\Database\Eloquent\Collection;
use App\Repositories\CoreRepository;
use App\Models\Franchise\Territory\Country as Model;

class CountryRepository extends CoreRepository
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
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]
     */
    public function getListCountry(): Collection
    {
        return $this->startConditions()
            ->with(['i18n'])
            ->get();
    }
}