<?php

declare(strict_types = 1);

namespace App\Services\ProductsMovements\Import\Products;

use App\Models\Shop\Badge\Badge;
use App\Models\Shop\Category\Category;
use App\Models\Shop\Room\Room;
use App\Models\Shop\Style\Style;
use App\Models\Shop\Supplier\Supplier;
use App\Services\ProductsMovements\Facades\ProductsMovements;

/**
 * Class ParametersBag
 *
 * @package App\Services\ProductsMovements\Import\Products
 */
class ParametersBag
{
    const PRICE_TYPE_STR = 'price_type_';
    const I18N_TYPE_STR = 'i18n_';
    const IMAGES_TYPE_STR = 'images';

    /**
     * @var array $arrayParams
     */
    public static $arrayParams = [
        'Название'                   => 'i18n_title',
        'Описание'                   => 'i18n_description',
        'Мета-заголовок'             => 'i18n_meta_title',
        'Мета-описание'              => 'i18n_meta_description',
        'Ширина'                     => 'width',
        'Высота'                     => 'height',
        'Длина'                      => 'length',
        'Вес'                        => 'weight',
        'Вес Доставки'               => 'delivery_weight',
        'Ширина Доставки'            => 'delivery_width',
        'Высота Доставки'            => 'delivery_height',
        'Длина Доставки'             => 'delivery_length',
        'Срок доставки'              => 'delivery_time',
        'Срок изготовления'          => 'production_time',
        'В наличии'                  => 'available',
        'Опубликовано'               => 'enabled',
        'Можно оплатить'             => 'is_payable',
        'Старая цена'                => 'price_type_1',
        'Цена продажи'               => 'price_type_7',
        'Акционная цена'             => 'price_type_6',
        'Минимальная розничная цена' => 'price_type_2',
        'Закупочная цена'            => 'price_type_4',
        'Категории'                  => 'categories',
        'Стили'                      => 'styles',
        'ID у поставщика'            => 'id_from_supplier',
        'GTIN'                       => 'gtin',
        'Поставщики'                 => 'suppliers',
        'Комнаты'                    => 'rooms',
        'Изображения'                => 'images',
        'Бейджи'                     => 'badges',
        'Грузовые места'             => 'cargo_places',
    ];

    /**
     * @var array $parameters
     */
    protected $parameters;

    /**
     * @var array $relations
     */
    protected $relations = [
        'styles',
        'suppliers',
        'rooms',
        'categories',
        'badges',
    ];

    /**
     * @var array $relationModel
     */
    protected $relationModel = [
        'styles'     => Style::class,
        'suppliers'  => Supplier::class,
        'rooms'      => Room::class,
        'categories' => Category::class,
        'badges'     => Badge::class,
    ];

    /**
     * @var array $attributes
     */
    protected $attributes;

    /**
     * ParametersBag constructor.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $parameters = $this->prepareParams($parameters);
//        $parameters = $this->filterEmptyParams($parameters);

        foreach ($this->relations as $relation) {
            $this->parameters[$relation] = [];
        }

        foreach ($parameters as $key => $value) {
            if (array_key_exists($key, self::$arrayParams)) {
                $attrName = self::$arrayParams[$key];

                // init price params
                if (false !== strpos($attrName, ParametersBag::PRICE_TYPE_STR)) {
                    $priceType = self::getPriceType($attrName);

                    if (null === $value || (is_string($value) && mb_strlen(trim($value)) === 0)) {
                        $this->parameters['prices'][$priceType]['RUB'] = null;

                        continue;
                    }

                    $this->parameters['prices'][$priceType]['RUB'] = $value;

                    continue;
                }

                // init i18n params
                if (false !== strpos($attrName, ParametersBag::I18N_TYPE_STR)) {
                    $i18nAttrName = substr($attrName, strlen(ParametersBag::I18N_TYPE_STR));

                    $this->parameters['i18n']['ru'][$i18nAttrName] = $value ?? '';

                    continue;
                }

                // init images param
                if (false !== strpos($attrName, ParametersBag::IMAGES_TYPE_STR)) {
                    if (null === $value || mb_strlen(trim($value)) === 0) {
                        $this->parameters[ParametersBag::IMAGES_TYPE_STR] = null;

                        continue;
                    }

                    $optionsArray = array_filter(explode(';', (string) $value));
                    $optionsArray = array_map(function ($item) {
                        return trim($item);
                    }, $optionsArray);

                    if (count($optionsArray)) {
                        $this->parameters[ParametersBag::IMAGES_TYPE_STR] = $optionsArray;
                    } else {
                        $this->parameters[ParametersBag::IMAGES_TYPE_STR][] = null;
                    }

                    continue;
                }

                // init cargo_places param
                if (false !== strpos($attrName, 'cargo_places')) {
                    $placesArray = explode('],', str_replace(' ', '',(string) $value));
                    $places = [];
                    foreach ($placesArray as $rawPlace) {
                        $place = [];
                        $trimmed = trim($rawPlace, ' []');
                        $exploded = explode(',', $trimmed);

                        foreach ($exploded as $key => $measure) {
                            if ($measure) {
                                switch ($key) {
                                    case 0:
                                        $place['width'] = (int) $measure;
                                        break;
                                    case 1:
                                        $place['height'] = (int) $measure;
                                        break;
                                    case 2:
                                        $place['length'] = (int) $measure;
                                        break;
                                    case 3:
                                        $place['weight'] = (int) $measure;
                                        break;
                                }
                            }
                        }

                        $places[] = $place;
                    }

                    $r = 1;
                    $this->parameters[$attrName] = $places;

                    continue;
                }

                // init relations
                if (in_array($attrName, $this->relations)) {
                    $optionsArray = explode(';', (string) $value);

                    if (count($optionsArray)) {
                        foreach ($optionsArray as $option) {
                            $option = trim($option);
                            preg_match_all("/(^\d+)/", $option, $out, PREG_UNMATCHED_AS_NULL);

                            if (!empty($out[1]) && !is_array($out[1][0]) && !is_null($out[1][0]) && strlen($out[1][0])) {
                                $optionId = (int) $out[1][0];
                            }

                            if (isset($optionId) && self::checkIfModelExists($attrName, $optionId)) {
                                $this->parameters[$attrName][] = $optionId;
                            }
                        }
                    } else {
                        $this->parameters[$attrName][] = null;
                    }

                    continue;
                }

                $this->parameters[$attrName] = $value;
            }
        }

        if (!array_filter(array_flatten($this->parameters['prices']))) {
            throw new \Exception('Не заполнены поля цен');
        }

        foreach (ProductsMovements::getAllAttributes() as $attribute) {
            $attributeName = $attribute->id.'|'.$attribute->i18n->first()->title;

            if (array_key_exists($attributeName, $parameters) && !is_null($parameters[$attributeName])) {
                $this->attributes[$attributeName] = $parameters[$attributeName];
            }
        }
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters ?? [];
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes ?? [];
    }

    /**
     * @param $name
     *
     * @return mixed|void
     */
    public function __get($name)
    {
        if (isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }

        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        return;
    }

    /**
     * @param string $attrName
     * @param int    $optionId
     *
     * @return bool
     */
    protected function checkIfModelExists(string $attrName, int $optionId): bool
    {
        if (!self::issetAttr($attrName)) {
            return false;
        }

        if ($attrName === 'badges') {
            return $this->relationModel[$attrName]::where('badge_type_id', '=', $optionId)->exists();
        }

        return $this->relationModel[$attrName]::where('id', '=', $optionId)->exists();
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function prepareParams(array $params): array
    {
        return collect($params)
            ->map(function ($item) {
                if ($item === '=TRUE()') {
                    return true;
                }

                return $item;
            })
            ->toArray();
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function filterEmptyParams(array $params): array
    {
        return array_filter($params, function ($key) {
            return array_key_exists($key, self::$arrayParams);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function issetAttr(string $name): bool
    {
        return !!$this->relationModel[$name] ?? false;
    }

    /**
     * @param string $attrName
     *
     * @return int
     */
    protected function getPriceType(string $attrName): int
    {
        return (integer) substr($attrName, strlen(ParametersBag::PRICE_TYPE_STR));
    }
}
