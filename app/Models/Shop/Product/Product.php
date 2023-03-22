<?php

namespace App\Models\Shop\Product;

use App\Contracts\Models\ITypedSources;
use App\Models\Shop\CargoPlace;
use App\Models\Shop\Sale\Sale;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\Models\Media;
use App\Support\Traits\Models\HasMediaTrait;

use App\Support\Traits\Models\StatusChangeable;
use App\Support\Traits\Models\Sluggable;
use App\Support\Traits\Models\RequestSaver;
use App\Support\Traits\Models\I18nTrait;
use App\Support\Traits\Models\HasRooms;
use App\Support\Traits\Models\HasStyles;

use MosseboShopCore\Support\Traits\Models\HasMorphRelation;
use MosseboShopCore\Support\Traits\Models\Shop\HasI18n;
use MosseboShopCore\Support\Traits\Models\Shop\HasEnabledStatus;
use MosseboShopCore\Contracts\Models\HasMorphRelation as HasMorphRelationInterface;
use MosseboShopCore\Models\Base\BaseModel;
use MosseboShopCore\Elasticsearch\Configurators\ProductIndexConfigurator;

use ScoutElastic\Searchable;

use App\Models\Shop\Badge\Badge;
use App\Models\Shop\Price\Price;
use App\Models\Shop\Category\Category;
use App\Models\Shop\Category\CategoryProduct;
use App\Models\Shop\Room\RoomProduct;
use App\Models\Shop\Style\StyleProduct;
use App\Models\Shop\Supplier\Supplier;

use App\Models\Shop\Attribute\LinkAttributeRelation as LinkAttribute;
use App\Models\Shop\Attribute\LinkAttributeRelationOption as LinkAttributeOption;

use App\Models\Shop\Attribute\Attribute;
use App\Models\Shop\Product\ProductAttribute;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int id
 * @property string id_from_supplier
 * @property string onOrder
 * @property int quantity
 * @property Carbon receiptDate
 * @property string production_time
 */
class Product extends BaseModel implements HasMedia, HasMorphRelationInterface, ITypedSources
{
    use HasMediaTrait,
        StatusChangeable,
        Sluggable,
        RequestSaver,
        I18nTrait,
        HasRooms,
        HasStyles,
        Searchable,
        HasI18n,
        HasEnabledStatus,
        HasMorphRelation,
        SoftDeletes;

    protected $indexConfigurator = ProductIndexConfigurator::class;

    protected $tableKey = 'Products';
    protected $relationFieldName = 'product_id';
    protected $mediaCollectionName = 'images';

    protected $fillable = [
        'quantity',
        'showed',
        'slug',
        'bought',
        'enabled',
        'is_payable',
        'width',
        'height',
        'length',
        'delivery_weight',
        'delivery_width',
        'delivery_height',
        'delivery_length',
        'weight',
        'available',
        'production_time',
        'delivery_time',
        'id_from_supplier',
        'onOrder',
        'receiptDate',
        'gtin',
    ];

    protected $dates = [
      'created_at',
      'updated_at',
    ];

    protected $mapping = [
      'properties' => [
        'index' => [
          'type' => 'text',
        ],
      ],
    ];

    protected $morphTypeName = 'product';

    protected $needsToSaveFromRequest = [
      'i18n',
      'categories',
      'rooms',
      'styles',
      'images',
      'prices',
      'related',
      'badges',
      'available',
      'production_time',
      'delivery_time',
      'suppliers',
      'cargo_places',
    ];

    protected $needsToSaveFromImport = [
        'i18n',
        'styles',
        'suppliers',
        'rooms',
        'categories',
        'prices',
        'badges',
        'cargo_places',
    ];

    protected $roomRelationModel = RoomProduct::class;
    protected $styleRelationModel = StyleProduct::class;

    public function toSearchableArray()
    {
        $index = [
          $this->id,
        ];

        foreach ($this->i18n as $translate) {
            $index[] = $translate->title;
            $index[] = $translate->description;
        }

        // foreach ($this->attributeOptions as $option) {
        //     foreach ($option->i18n as $translate) {
        //         $index[] = $translate->value;
        //     }
        // }

        foreach ($this->categories as $category) {
            foreach ($category->i18n as $translate) {
                $index[] = $translate->title;
            }
        }

        foreach ($this->styles as $style) {
            foreach ($style->i18n as $translate) {
                $index[] = $translate->title;
            }
        }

        foreach ($this->rooms as $room) {
            foreach ($room->i18n as $translate) {
                $index[] = $translate->title;
            }
        }

        $index = array_unique($index);

        return [
          'index' => join(' ', preg_replace('/\s\s+/', ' ', $index)),
        ];
    }


    /**
     * Адрес товара на сайте.
     *
     * @return string
     */
    public function url()
    {
        return "/goods/{$this->id}";
    }


    public function scopeWhereStyle($query, $styleId)
    {
        $styleRelationsTableName = config('tables.StyleProducts');
        $productTableName = config('tables.Products');

        return $query->join($styleRelationsTableName, function ($join) use ($styleRelationsTableName, $productTableName, $styleId) {
            $join->on("{$styleRelationsTableName}.product_id", '=', "{$productTableName}.id");

            if (is_array($styleId)) {
                $join->whereIn("{$styleRelationsTableName}.style_id", $styleId);
            } else {
                $join->where("{$styleRelationsTableName}.style_id", $styleId);
            }
        });
    }


    public function scopeWhereRoom($query, $roomId)
    {
        $roomRelationsTableName = config('tables.RoomProducts');
        $productTableName = config('tables.Products');

        return $query->join($roomRelationsTableName, function ($join) use ($roomRelationsTableName, $productTableName, $roomId) {
            $join->on("{$roomRelationsTableName}.product_id", '=', "{$productTableName}.id");

            if (is_array($roomId)) {
                $join->whereIn("{$roomRelationsTableName}.room_id", $roomId);
            } else {
                $join->where("{$roomRelationsTableName}.room_id", $roomId);
            }
        });
    }


    public function scopeWhereCategory($query, $categoryId)
    {
        $categoryRelationsTableName = config('tables.CategoryProducts');
        $productTableName = config('tables.Products');

        return $query->join($categoryRelationsTableName, function ($join) use ($categoryRelationsTableName, $productTableName, $categoryId) {
            $join->on("{$categoryRelationsTableName}.product_id", '=', "{$productTableName}.id");

            if (is_array($categoryId)) {
                $join->whereIn("{$categoryRelationsTableName}.category_id", $categoryId);
            } else {
                $join->where("{$categoryRelationsTableName}.category_id", $categoryId);
            }
        });
    }

    public function scopeEnabled($query)
    {
        $supplierTableName = config('tables.Suppliers');
        $productTableName = config('tables.Products');
        $suppliersIds = $this->suppliers->map(function ($item) {
            return $item->id;
        })->toArray();
        $suppliersIdsStr = implode( ', ', $suppliersIds);

        return $query->addSelect(\DB::raw("{$supplierTableName}.enabled as supplier_enabled"))
          ->leftJoin("{$supplierTableName}", function ($join) use ($supplierTableName, $productTableName, $suppliersIdsStr) {
              $join->on("{$supplierTableName}.id", 'IN', "({$suppliersIdsStr})")
                ->where("{$productTableName}.enabled", 1)
                ->groupBy("supplier_enabled")
                ->where("{$supplierTableName}.enabled", true);
          });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function prices(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Price::class, 'item');
    }

    public function categoryRelations()
    {
        return $this->hasMany(CategoryProduct::class, $this->relationFieldName);
    }

    // todo: move to core.
    public function cargoPlaces()
    {
        return $this->hasMany(CargoPlace::class, $this->relationFieldName);
    }

    public function categories()
    {
        return $this->hasManyThrough(
          Category::class, CategoryProduct::class,
          $this->relationFieldName, 'id', 'id', 'category_id'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class);
    }

    /**
     * @return MorphOne
     */
    public function sale(): MorphOne
    {
        return $this->morphOne(Sale::class, 'item');
    }

    //zvon
    public function attribute()
    {
        return $this->belongsToMany(Attribute::class, 'shop_product_attributes',
                                    'product_id', 'attribute_id');
    }


    public function linkedAttributes()
    {
        return $this
            ->hasMany(LinkAttribute::class, 'relation_id', 'id')
            ->where('relation', '=', 'product');
    }


    public function attributeOptions()
    {
        return $this
            ->hasManyThrough(
                LinkAttributeOption::class,
                LinkAttribute::class,
                'id',
                'id',
                'attribute_relation_id',
                'id'
            )
            ->where('relation', 'product');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }

    public function relatedRelations()
    {
        return $this->hasMany(RelatedProduct::class, $this->relationFieldName);
    }

    public function related()
    {
        return $this->hasManyThrough(
          Product::class, RelatedProduct::class,
          $this->relationFieldName, 'id', 'id', 'related_id'
        );
    }

    public function badges()
    {
        return $this->morphMany(Badge::class, 'item');
    }

    /**
     * Задает преобразователи изображений.
     *
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('large')
          ->fit(Manipulations::FIT_MAX, 1920, 1920)
          ->background('fff')
          ->performOnCollections('images');

        $this->addMediaConversion('medium')
          ->fit(Manipulations::FIT_MAX, 800, 800)
          ->background('fff')
          ->withResponsiveImages()
          ->performOnCollections('images');

        $this->addMediaConversion('small')
          ->fit(Manipulations::FIT_MAX, 400, 400)
          ->background('fff')
          ->withResponsiveImages()
          ->performOnCollections('images');

        $this->addMediaConversion('thumb')
          ->fit(Manipulations::FIT_MAX, 160, 160)
          ->background('fff')
          ->withResponsiveImages()
          ->nonQueued()
          ->performOnCollections('images');

        $this->addMediaConversion('small')
          ->fit(Manipulations::FIT_MAX, 400, 400)
          ->background('fff')
          ->withResponsiveImages()
          ->nonQueued()
          ->performOnCollections('temp');
    }


    /**
     * Сохранение категорий товара.
     *
     * @param array $categoryIds
     */
    protected function _saveCategories(array $categoryIds = [])
    {
        $categoryRelations = $this->categoryRelations();
        $categoryRelations->delete();

        foreach ($categoryIds as $categoryId) {
            $categoryRelations->save(new CategoryProduct(['category_id' => $categoryId]));
        }
    }

    /**
     * Сохранение цен.
     *
     * @param array $prices
     */
    protected function _savePrices(array $pricesData = [])
    {
        $productPrices = $this->prices();
        $productPrices->delete();
        $pricesToSave = [];

        foreach ($pricesData as $priceTypeId => $pricesByCurrencies) {
            foreach ($pricesByCurrencies as $currencyCode => $priceValue) {
                if (empty($priceValue)) {
                    continue;
                }

                $productPrices->save(new Price($pricesToSave[] = [
                  'price_type_id' => $priceTypeId,
                  'currency_code' => $currencyCode,
                  'value' => $priceValue,
                ]));
            }
        }
    }


    protected function _saveRelated(array $relatedIds)
    {
        $relatedRelations = $this->relatedRelations();
        $relatedRelations->delete();

        foreach ($relatedIds as $relatedId) {
            $relatedRelations->save(new RelatedProduct([
              'related_id' => $relatedId,
            ]));
        }
    }

    protected function _saveBadges(array $badgeTypeIds)
    {
        $badges = $this->badges();
        $badges->delete();

        foreach ($badgeTypeIds as $badgeTypeId) {
            $badges->save(new Badge([
              'badge_type_id' => $badgeTypeId,
            ]));
        }
    }

    /**
     * @param array $suppliersIds
     */
    protected function _saveSuppliers(array $suppliersIds)
    {
        $this->suppliers()->sync($suppliersIds);
    }

    /**
     * @param array
     */
    protected function _saveCargoPlaces($cargoPlaces)
    {
        $places = $this->cargoPlaces();
        $places->delete();

        if (!empty(array_filter($cargoPlaces))) {
            foreach ($cargoPlaces as $cargoPlace) {
                $places->save(new CargoPlace([
                    'width'  => $cargoPlace['width'],
                    'height' => $cargoPlace['height'],
                    'length' => $cargoPlace['length'],
                    'weight' => $cargoPlace['weight'],
                ]));
            }
        }
    }

    /**
     * Получение данных для отправки по api.
     *
     * @param $id
     * @param string $needsToConnect
     * @return array
     */
    public static function getData($id, $needsToConnect = 'all'): array
    {
        if ($needsToConnect === 'all') {
            $needsToConnect = ['i18n', 'prices', 'images', 'categories'];
        }

        if ($needsToConnect === 'nothig') {
            $needsToConnect = [];
        }

        if (!is_array($needsToConnect)) {
            $needsToConnect = [$needsToConnect];
        }

        $query = self::where('id', $id);

        foreach ($needsToConnect as $connect) {
            if (method_exists($query, $connect)) {
                $query = $query->with($connect);
            }
        }

        return $query->first()->toArray();
    }
}

