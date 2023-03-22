<?php

namespace App\Http\Controllers;

use App\Models;
use Illuminate\Support\Facades\Cache;

// todo: либо список репозиториев, либо методы

class DataHandler
{
    protected static $repositories = [
        'categories'    => \Categories::class,
        'rooms'         => \Rooms::class,
        'styles'        => \Styles::class,
        'currencies'    => \Currencies::class,
        'languages'     => \Languages::class,
        'price-types'   => \PriceTypes::class,
        'badge-types'   => \BadgeTypes::class,
        'roles'         => \AdminRoles::class,
        'banner-places' => \BannerPlaces::class,
    ];

    protected static $cacheKey = 'interactionData';

    /**
     * Проверка - является ли ключ устаревшим.
     *
     * @param $key
     * @return bool
     */
    public function keyIsRelevant($key)
    {
        return $key == static::getRelevantKey();
    }

    /**
     * Получение свежего ключа.
     *
     * @return mixed
     */
    public static function getRelevantKey()
    {
        return Cache::remember(static::$cacheKey, 18000, function () {
            return md5(uniqid());
        });
    }

    /**
     * Чистка кэша.
     *
     * @param null $dataType
     */
    public static function clearCache($dataTypeOrModelClassName = false)
    {
        if (! $dataTypeOrModelClassName) {
            static::clearAllCache();
            return;
        }

        if (isset(static::$repositories[$dataTypeOrModelClassName])) {
            static::$repositories[$dataTypeOrModelClassName]::clearCache();
            Cache::forget(static::$cacheKey);
            return;
        }

//        $modelClassName = $dataTypeOrModelClassName;
//
//        foreach (static::$repositories as $repo) {
//            if ($repo::getModelClassName() === $modelClassName) {
//                $repo::clearCache();
//                \Cache::forget(static::$cacheKey);
//                return;
//            }
//        }

        static::clearAllCache();
    }

    public static function clearAllCache()
    {
        foreach (static::$repositories as $repository) {
            $repository::clearCache();
        }

        Cache::forget(static::$cacheKey);
    }

    /**
     * Сбор данных.
     *
     * @param array $labels
     * @return array|mixed
     */
    public static function get(Array $labels = [])
    {
        $data = [];

        foreach ($labels as $label) {
            $methodName = static::_getMethodName($label);

            if (method_exists(__CLASS__, $methodName)) {
                try {
                    $data[$label] = call_user_func([__CLASS__, $methodName]);
                } catch (\Exception $e) {
                    //dd($e);
                }
            }
            elseif (isset(static::$repositories[$label])) {
                $data[$label] = static::_getRepository($label)::all();
            }
        }

        return $data;
    }

    protected static function _getMethodName($label)
    {
        $methodName = '_get';

        foreach (explode('-', $label) as $value) {
            $methodName .= ucfirst($value);
        }

        return $methodName;
    }

    /**
     *
     * @param $label
     * @return mixed
     */
    protected static function _getRepository($label)
    {
        return static::$repositories[$label];
    }

    /**
     * Дерево категорий.
     *
     * @return mixed
     */
    protected static function _getCategoriesTree()
    {
        return static::_getRepository('categories')::getTree([
            'i18n'
        ]);
    }

    /**
     * Поставщики.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    protected static function _getSuppliers()
    {
        return Models\Shop\Supplier\Supplier::all();
    }

    protected static function _getPriceTypes()
    {
        return static::_getRepository('price-types')::getCollection([
            'i18n'
        ]);
    }

    protected static function _getBadgeTypes()
    {
        return static::_getRepository('badge-types')::getCollection([
            'i18n'
        ]);
    }

    protected static function _getRooms()
    {
        return static::_getRepository('rooms')::getCollection([
            'i18n'
        ]);
    }

    protected static function _getStyles()
    {
        return static::_getRepository('styles')::getCollection([
            'i18n'
        ]);
    }
}