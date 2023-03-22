<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Api\ApiController;

use App\Models\Shop\Promo\PromoCode;
use App\Models\Shop\Promo\PromoUse;

use App\Http\Resources\Shop\Promo\PromoCodeEditResource;
use App\Http\Resources\Shop\Promo\PromoCodeTableResource;

use App\Support\Traits\Controllers\Creatable;
use App\Support\Traits\Controllers\Updatable;
use App\Support\Traits\Controllers\Deleteable;
use App\Support\Traits\Controllers\StatusChangeable;
use App\Support\Traits\Controllers\PositionChangeable;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class PromoCodeController extends ApiController
{
    use Creatable, Updatable, Deleteable, StatusChangeable, PositionChangeable;

    protected static $modelClass = PromoCode::class;
    protected static $entityName = 'promo-code';
    protected static $editResource = PromoCodeEditResource::class;
    protected static $tableResource = PromoCodeTableResource::class;

    use Deleteable {
        destroy as deleteableDestroy;
    }

    public function index()
    {
        $pagination = $this->_paginate($this->request->all());

        return [
            'promo-codes' => static::toResource($pagination->getCollection()),
            'perPage'     => $pagination->perPage(),
            'page'        => $pagination->currentPage(),
            'totalRows'   => $pagination->total(),
        ];
    }

    public function destroy($id)
    {
        $promoUsed = PromoUse::where('promo_code_id', $id)->exists();

        if ($promoUsed) {
            return [
                'status' => 'error',
                'message' => 'Промокод был использован, и не может быть удален.'
            ];
        }

        return $this->deleteableDestroy($id);
    }

    //todo: Убрать две функции ниже хз куда
    //todo: Оптимизировать запросы
    public function _paginate(Array $params = []): LengthAwarePaginator
    {
        $pagination = $this->_makePagination($params);

        if ($pagination->isEmpty() && $params['page'] > 1) {
            $page = round($pagination->total() / $params['perPage']);
            $params['page'] = $page <= 0 ? 1 : $page;

            $pagination = $this->_makePagination($params);
        }

        return $pagination;
    }

    public function _makePagination(Array $params): LengthAwarePaginator
    {
        extract($params, EXTR_OVERWRITE);

        $query = static::$modelClass::with('conditions', 'uses');

        if ($type === 'enabled') {
            $query = $query->where('enabled', true);
        }

        if ($type === 'old') {
            $query = $query
                ->where('quantity', 0)
                ->orWhere('date_finish', '<', Carbon::now());
        }

        if ($type === 'disabled') {
            $query = $query->where('enabled', false);
        }

        return $query->orderBy('id', 'desc')
            ->paginate($perPage, null, null, $page);
    }
}