<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Api\ApiController;

use App\Http\Requests\ApiRequest;
use App\Models\Shop\Attribute\Attribute;

use App\Models\Shop\Attribute\AttributeI18n;
use App\Models\Shop\Attribute\AttributeOption;
use Illuminate\Support\Facades\Cache;
use MosseboShopCore\Support\Facades\Attributes;
use Illuminate\Support\Facades\Event;

use App\Http\Resources\Shop\Attribute\AttributeEditResource;
use App\Http\Resources\Shop\Attribute\AttributesTableResource;
use App\Http\Resources\Shop\Attribute\AttributeOptionResource;

use App\Support\Traits\Controllers\Creatable;
use App\Support\Traits\Controllers\Updatable;
use App\Support\Traits\Controllers\Deleteable;
use App\Support\Traits\Controllers\StatusChangeable;
use App\Support\Traits\Controllers\PositionChangeable;

use App\Repositories\Product\Attribute\AttributeRepository;

class AttributeController extends ApiController
{
    use Creatable, Updatable, Deleteable, StatusChangeable, PositionChangeable;

    protected static $modelClass = Attribute::class;
    protected static $entityName = 'attribute';
    protected static $editResource = AttributeEditResource::class;
    protected static $tableResource = AttributesTableResource::class;

    private $attributeRepository;

    public function __construct(ApiRequest $request)
    {
        parent::__construct($request);

        $this->attributeRepository = app(AttributeRepository::class);
    }

    public function index()
    {
        $ids = request('ids');

        if ($ids) {
//            $attributes = Cache::remember('attributesAdminApiResponse::' . implode('-', $ids), now()->addMinutes(30), function () use ($ids) {
//                return static::toResource(Attributes::getCollection('i18n')
//                    ->whereIn('id', $ids));
//            });

//            dd($this->attributeRepository->getCollectionRaw($ids));

            $attributes = Cache::remember('attributesAdminApiResponse::' . implode('-', $ids), now()->addMinutes(30), function () use ($ids) {
                return static::toResource($this->attributeRepository->getCollectionRaw($ids));
            });
        } else {
//            $attributes = Cache::remember('attributesAdminApiResponse', now()->addMinutes(30), function () {
//                return static::toResource(Attributes::getCollection('i18n'));
//            });

            $attributes = Cache::remember('attributesAdminApiResponse', now()->addMinutes(30), function () {
                return static::toResource($this->attributeRepository->getCollection());
            });
        }

        return [
            'attributes' => $attributes,
        ];
    }


    public function options(Attribute $attribute)
    {
        return response()->json([
            'status' => 'success',
            'options' => AttributeOptionResource::collection($attribute->options()->with('i18n')->get()),
        ], 200);
    }


    public function create()
    {
        //return request()->all();

        $attribute = Attribute::create([
            'selectable' => false,
            'enabled' => true,
            'layout_class' => ''
        ]);

        $i18n = AttributeI18n::create([
            'attribute_id' => $attribute->id,
            'language_code' => 'ru',
            'title' => request('name')
        ]);

        return response()->json([
            'status' => 'success',
            'attribute' => Attribute::with('i18n')->find($attribute->id),
        ], 200);
    }


}
