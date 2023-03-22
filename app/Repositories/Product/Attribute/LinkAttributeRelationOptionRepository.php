<?php
declare(strict_types=1);

namespace App\Repositories\Product\Attribute;

use App\Repositories\CoreRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Shop\Attribute\LinkAttributeRelationOption as Model;

class LinkAttributeRelationOptionRepository extends CoreRepository
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
     * @param  int  $idAttributeRelation
     * @param  int  $idOption
     * @return bool
     */
    public function addLinkAttributeOption(int $idAttributeRelation, int $idOption): bool
    {
        $result = false;

        try {
            DB::beginTransaction();

            $this->startConditions()->create([
                'attribute_relation_id' => $idAttributeRelation,
                'option_id' => $idOption,
                                             ]);

            DB::commit();
            $result = true;
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception->getMessage());
        }

        return $result;
    }

    /**
     * @param  int  $id
     * @return bool|null
     */
    public function deleteIdLinkAttributeOption(int $id): int
    {
        $result = 0;

        try {
            $result = $this->startConditions()->destroy($id);
        } catch (\Exception $exception) {
            dd($exception);
        }

        return $result;
    }

    /**
     * @param  array  $ids
     * @return Collection|null
     */
    public function getLinkAttributeOption(array $ids = []): ?Collection
    {
        return $this->startConditions()
            ->with(['option.i18n'])
            ->whereIn('attribute_relation_id', $ids)
            ->get();
    }

    /**
     * @param $attribute_relation_id
     * @return bool
     */
    public function deleteAttributeRelationOption($attribute_relation_id): int
    {
        return $this->startConditions()
            ->where('attribute_relation_id', $attribute_relation_id)
            ->delete();
    }
}