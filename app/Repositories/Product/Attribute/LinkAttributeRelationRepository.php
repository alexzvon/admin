<?php
declare(strict_types=1);

namespace App\Repositories\Product\Attribute;

use App\Repositories\CoreRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Shop\Attribute\LinkAttributeRelation as Model;

class LinkAttributeRelationRepository extends CoreRepository
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
     * @param  string  $relation
     * @param  int  $relation_id
     * @param  int  $attribute_id
     * @return Model|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getLinkAttributeOptionsRelation(string $relation, int $relation_id, int $attribute_id): ?Model
    {
        if ('' !== $relation && 0 < $relation_id && 0 < $attribute_id) {
            return $this->startConditions()
                ->with('linkedOptions')
                ->where('enabled', '=', true)
                ->where('relation', '=', $relation)
                ->where('relation_id', '=', $relation_id)
                ->where('attribute_id', '=', $attribute_id)
                ->first();
        }

        return null;
    }

    /**
     * @param  string  $relation
     * @param  int  $relation_id
     * @return Collection|null
     */
    public function getLinkAttributeRelation(string $relation, int $relation_id): ?Collection
    {
        $result = null;

        if ('' !== $relation && 0 < $relation_id) {
            $result = $this->startConditions()
                ->with('linkedOptions')
                ->where('enabled', '=', true)
                ->where('relation', '=', $relation)
                ->where('relation_id', '=', $relation_id)
                ->get();
        }

        return $result;
    }

    /**
     * @param $attribute_relation_id
     * @return int
     */
    public function deleteAttributeRelation($attribute_relation_id): int
    {
        return $this->startConditions()->destroy($attribute_relation_id);
    }

    /**
     * @param $productId
     * @return Collection
     */
    public function getAttributeIdProduct($productId): Collection
    {
        return $this->startConditions()
            ->select('attribute_id')
            ->where('relation_id', $productId)
            ->groupBy('attribute_id')
            ->get();
    }

    /**
     * @param  int  $id
     * @param  bool  $selectable
     * @param  bool  $is_products
     * @return bool
     */
    public function updateStateAttributeRealation(int $id, bool $selectable, bool $isProducts): bool
    {
        $result = false;

        try {
            DB::beginTransaction();

            $result = $this->startConditions()
                ->where('id', $id)
                ->update(['selectable' => $selectable, 'is_products' => $isProducts]);

            DB::commit();
            $result = true;
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }

        return $result;
    }
}