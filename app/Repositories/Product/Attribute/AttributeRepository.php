<?php
declare(strict_types=1);

namespace App\Repositories\Product\Attribute;

use App\Repositories\CoreRepository;
use Illuminate\Support\Collection;
use App\Models\Shop\Attribute\Attribute as Model;

use App\Models\Shop\Attribute\AttributeOption;
use Illuminate\Support\Facades\DB;

class AttributeRepository extends CoreRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getModelClass(): string
    {
        return Model::class;
    }

    public function getNotIdCollection(array $ids): Collection
    {
        return $this->startConditions()
            ->with(['i18n'])
            ->whereNotIn('id', $ids)
            ->get();
    }

    /**
     * @param  array  $ids
     * @return Collection
     */
    public function getAttributeWithI18n(array $ids): Collection
    {
        return $this->startConditions()
            ->with(['i18n'])
            ->orderBy('position', 'asc')
            ->whereIn('id', $ids)
            ->get();
    }

    /**
     * @return Collection
     */
    public function getCollection(): Collection
    {
        return $this->startConditions()
            ->with(['i18n'])
            ->orderBy('position', 'asc')
            ->get();
    }

    /**
     * @param  array  $ids
     * @return Collection
     */
    public function getCollectionRaw(array $ids): Collection
    {
        return $this->startConditions()
                ->with([
                           'i18n',
                           'options' => function ($query) {
                               $query
                                   ->with('i18n')
                                   ->orderBy('position', 'asc');
                           }
                       ])
                ->orderBy('position', 'asc')
                ->whereIn('id', $ids)
                ->get();
    }
}