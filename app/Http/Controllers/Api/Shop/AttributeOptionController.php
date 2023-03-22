<?php namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;

use App\Models\Shop\Attribute\AttributeOption;
use App\Models\Shop\Attribute\AttributeOptionI18n;

use App\Http\Resources\Shop\Attribute\AttributeOptionResource;
use Illuminate\Support\Facades\Cache;

class AttributeOptionController extends Controller
{

    public function optionsForManyAttributes()
    {
        $ids = request('ids');
        $sameCollectionAttrId = config('shop.same_collection.same_collection_attr_id', null);
        $ids = array_filter($ids, function ($id) use ($sameCollectionAttrId) {
            return (int) $id !== $sameCollectionAttrId;
        });

        $attributesOptions = Cache::remember('attributesOptions::' . implode('-', $ids), now()->addMinutes(30), function () use ($ids) {
            return AttributeOptionResource::collection(AttributeOption::with('i18n')
                ->whereIn('attribute_id', $ids)
                ->get());
        });

        return response()->json([
            'status' => 'success',
            'options' => $attributesOptions,
        ], 200);
    }

    public function optionsForManyUsedAttributes()
    {
        $ids = request('ids');

        $attributesOptions = Cache::remember('attributesOptions::' . implode('-', $ids), now()->addMinutes(30), function () use ($ids) {
            return AttributeOptionResource::collection(AttributeOption::with('i18n')
                ->whereIn('id', $ids)
                ->get());
        });

        return response()->json([
            'status' => 'success',
            'options' => $attributesOptions,
        ], 200);
    }


    public function create()
    {
        $option = AttributeOption::create([
            'attribute_id' => request('attribute_id'),
            'enabled' => true,
        ]);

        $i18n = AttributeOptionI18n::create([
            'option_id' => $option->id,
            'language_code' => 'ru',
            'value' => request('title', 'Noname')
        ]);

        return response()->json([
            'status' => 'success',
            'option' => AttributeOption::with('i18n')->find($option->id),
        ]);
    }


    public function delete()
    {

    }


    public function update()
    {

    }

}