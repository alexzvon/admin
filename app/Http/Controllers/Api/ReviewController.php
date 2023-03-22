<?php

namespace App\Http\Controllers\Api;

use App\Events\Entity\EntityStatusChanged;
use App\Models\Review;
use App\Http\Resources\ReviewResource;

use App\Support\Traits\Controllers\Creatable;
use App\Support\Traits\Controllers\Updatable;
use App\Support\Traits\Controllers\Deleteable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReviewController extends ApiController
{
    use Creatable, Updatable, Deleteable;

    protected static $modelClass = Review::class;
    protected static $entityName = 'review';
    protected static $editResource = ReviewResource::class;
    protected static $tableResource = ReviewResource::class;

    public function index()
    {
        $pagination = $this->_paginate($this->request->all());

        return [
            'reviews'     => static::toResource($pagination->getCollection()),
            'perPage'     => $pagination->perPage(),
            'page'        => $pagination->currentPage(),
            'totalRows'   => $pagination->total(),
            'unconfirmed' => Review::where('enabled', 1)->where('confirmed', 0)->count(),
            'deleted'     => Review::where('enabled', 0)->count(),
        ];
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

                $query = static::$modelClass::with(['item' => function($query) {
                    $query->with('i18n');
                }]);

                if ($type === 'confirmed') {
                    $query = $query->where('enabled', 1)->where('confirmed', true);
                }

                if ($type === 'unconfirmed') {
                    $query = $query->where('enabled', 1)->where('confirmed', false);
                }

                if ($type === 'removed') {
                    $query = $query->where('enabled', false);
                }

                return $query->orderBy('id', 'desc')
                    ->with('user', 'likesCounter', 'dislikesCounter', 'likes', 'dislikes')
                    ->paginate($perPage, null, null, $page);
            }

    /**
     * Смена статуса сущности.
     *
     * @param  Integer
     * @return Repsonse
     */
    public function status($id)
    {
        $model = $this->getModel($id);

        if ($model->confirmed) {
            $model->confirmed = false;
        }
        else {
            $model->confirmed = true;
        }

        $model->save();

        \Event::fire(new EntityStatusChanged($model));

        return response()->json([
            'status' => 'success',
            'message' => $this->lang("status." . ($model->confirmed ? 'enabled' : 'disabled'), ['id' => $model[$model->getKeyName()]])
        ], 201);
    }
}
