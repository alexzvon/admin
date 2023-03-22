<?php
declare(strict_types=1);

namespace App\Repositories\Product\Attribute;

use App\Repositories\CoreRepository;
use App\Models\Shop\Attribute\AttributeOption as Model;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Log;

class AttributeOptionRepository extends CoreRepository
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
     * @param  array  $inAttributesIds
     * @return Collection
     */
    public function getAttributesOptionsInIds(array $inAttributesIds = []): Collection
    {
        return $this->startConditions()
            ->with(['i18n'])
            ->whereIn('attribute_id', $inAttributesIds)
            ->get();
    }


    public function getAttributesOptionsIdSearchLimit(
        int $idAttr, string $search = '', int $limit = 0, int $attribute_option_id = 0): ?Collection
    {
        $result = null;

        $limit = 0 === $limit ? 'All' : $limit;


        $ccc = 0;
        $cikl = 1;

        $result = $this->startConditions()
            ->where('attribute_id', $idAttr)
            ->where('id', '>', $attribute_option_id)
            ->orderBy('id')
            ->simplePaginate(50);
//            ->chunk(100, function($options)use($ccc, $cikl){
//                $ccc += count($options);
//                Log::alert('Cikl =' . $cikl++);
//                Log::alert('Count options' . count($options));
//                Log::alert('Total options' . $ccc);
//                Log::alert('==========================================');
//            });

//            ->limit($limit)
//            ->get();




//        if (0 < $idAttr && 0 < strlen($search)) {
//            $result = $this->startConditions()
//                ->with([
//                           'i18n' => function ($query) use ($search) {
//                               return $query->where('value', 'ILIKE', '%'.$search.'%');
//                           }
//                       ])
//                ->where('attribute_id', $idAttr)
//                ->where('id', '>', $attribute_option_id)
//                ->orderBy('id')
//                ->limit($limit)
//                ->get();
//        } else if (0 < $idAttr && 0 == strlen($search)) {
//            $result = $this->startConditions()
//                ->with(['i18n'])
//                ->where('attribute_id', $idAttr)
//                ->where('id', '>', $attribute_option_id)
//                ->orderBy('id')
//                ->limit($limit)
//                ->get();
//        }

        return $result;
    }

    /**
     * @param  int  $idAttr
     * @param  string  $search
     * @param  int  $limit
     * @param  int  $offset
     * @return Collection|null
     */
    public function getAttributesOptionsInIdSearchLimit(
        int $idAttr, string $search = '', int $limit = 0, int $offset = 0): ?Collection
    {

//        dump('getAttributesOptionsInIdSearchLimit(int $idAttr, string $search = "", int $limit = 0, int $offset = 0): ?Collection');
//        dd($idAttr, $search, $limit, $offset);

        $result = $this->startConditions()
            ->with(['i18n'])
            ->where('attribute_id', $idAttr)
            ->orderBy('id')
            ->offset($offset)
            ->simplePaginate(50);


        return null;



        if (0 < $idAttr && 0 < strlen($search) && 0 < $limit && 0 < $offset) {
            return $this->startConditions()
                ->with([
                           'i18n' => function($query)use($search) {
                               return $query->where('value', 'ILIKE', '%'.$search.'%');
                           }
                       ])
                ->where('attribute_id', $idAttr)
                ->orderBy('id')
                ->offset($offset)
                ->limit($limit)
                ->get();
        } elseif (0 < $idAttr && 0 < strlen($search) && 0 < $limit && 0 == $offset) {
            return $this->startConditions()
                ->with([
                           'i18n' => function($query)use($search) {
                               return $query->where('value', 'ILIKE', '%'.$search.'%');
                           }
                       ])
                ->where('attribute_id', $idAttr)
                ->orderBy('id')
                ->limit($limit)
                ->get();
        } elseif (0 < $idAttr && 0 < strlen($search) && 0 == $limit && 0 == $offset) {
            return $this->startConditions()
                ->with([
                           'i18n' => function($query)use($search) {
                               return $query->where('value', 'ILIKE', '%'.$search.'%');
                           }
                       ])
                ->where('attribute_id', $idAttr)
                ->orderBy('id')
                ->get();
        } elseif (0 < $idAttr && 0 == strlen($search) && 0 == $limit && 0 == $offset) {
            return $this->startConditions()
                ->with(['i18n'])
                ->where('attribute_id', $idAttr)
                ->orderBy('id')
                ->get();
        } elseif (0 < $idAttr && 0 == strlen($search) && 0 < $limit && 0 == $offset) {
            return $this->startConditions()
                ->with(['i18n'])
                ->where('attribute_id', $idAttr)
                ->orderBy('id')
                ->limit($limit)
                ->get();
        } elseif (0 < $idAttr && 0 == strlen($search) && 0 < $limit && 0 < $offset) {
            return $this->startConditions()
                ->with(['i18n'])
                ->where('attribute_id', $idAttr)
                ->orderBy('id')
                ->offset($offset)
                ->limit($limit)
                ->get();
        }

        return null;
    }
}