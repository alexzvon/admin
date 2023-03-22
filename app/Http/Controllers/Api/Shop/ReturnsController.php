<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\Shop\ReturnsListRequest;
use App\Http\Requests\Shop\ReturnsEditRequest;
use App\Http\Requests\Shop\ReturnsSaveRequest;
use App\Repositories\Shop\ReturnsRepository;
use App\Http\Resources\Shop\Returns\ReturnsListCollection;
use App\Http\Resources\Shop\Returns\ReturnsEditResource;


class ReturnsController extends Controller
{
    private $returnsRepository;

    public function __construct()
    {
        $this->returnsRepository = app(ReturnsRepository::class);
    }

    /**
     * таблица
     * @param ReturnsListRequest $request
     * @return ReturnsListCollection
     */
    public function list(ReturnsListRequest $request): ReturnsListCollection
    {
        return new ReturnsListCollection($this->returnsRepository->getList($request->all()));
    }

    /**
     * редактировать
     * @param ReturnsEditRequest $request
     * @return ReturnsEditResource
     */
    public function edit(ReturnsEditRequest $request): ReturnsEditResource
    {
        return ReturnsEditResource::make($this->returnsRepository->getEdit($request->all()));
    }

    /**
     * сохранить
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(ReturnsSaveRequest $request)
    {
        return response()->json($this->returnsRepository->saveEdit($request->all()));
    }

}
