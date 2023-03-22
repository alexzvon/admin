<?php
declare(strict_types=1);

namespace App\Repositories\Product;

use App\Repositories\CoreRepository;
use App\Models\Shop\Product\OtherProductOptions as Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OtherProductOptionsRepository extends CoreRepository
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
     * @param  int  $parent_id
     * @param  int  $children_id
     * @return bool
     */
    public function createParentChildren(int $parent_id, int $children_id): bool
    {
        $result = true;

        try {
            DB::beginTransaction();

            $this->startConditions()
                ->create([
                    'parent_id' => $parent_id,
                    'children_id' => $children_id,
                         ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            $result = false;
            dump($parent_id . ' -- ' . $children_id . ' -- ' . $exception->getMessage());
        }

        return $result;
    }

    /**
     * @param  int  $parent_id
     * @param  int  $children_id
     * @return bool
     */
    public function deleteParentChildren(int $parent_id, int $children_id): bool
    {
        return (bool)$this->startConditions()
            ->where('parent_id', $parent_id)
            ->where('children_id', $children_id)
            ->delete();
    }

    /**
     * @param  int  $product_id
     * @return Collection
     */
    public function getIdsOtherProductOptions(int $product_id): Collection
    {
        return $this->startConditions()
            ->where('parent_id', $product_id)
            ->get();
    }

    /**
     * @param  int  $parent_id
     * @return Collection
     */
    public function getOtherProductOptions(int $parent_id): Collection
    {
        return $this->startConditions()
            ->with(['childrenProductI18n'])
            ->where('parent_id', $parent_id)
            ->get();
    }
}