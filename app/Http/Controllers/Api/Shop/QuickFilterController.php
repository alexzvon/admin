<?php

namespace App\Http\Controllers\Api\Shop;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Shop\QuickFilter\StoreRequest;
use App\Http\Requests\Shop\QuickFilter\UpdateRequest;
use App\Http\Requests\Shop\QuickFilter\IndexRequest;
use App\Http\Resources\Shop\QuickFilter\ShowQuickFilterResource;
use App\Http\Resources\Shop\QuickFilter\IndexQuickFilterCollection;
use App\Repositories\Shop\CategoryRepository;
use App\Repositories\Shop\QuickFilterRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class QuickFilterController extends Controller
{
    private $categoryRepository;
    private $quickFilterRepository;

    public function __construct()
    {
        $this->categoryRepository = app(CategoryRepository::class);
        $this->quickFilterRepository = app(QuickFilterRepository::class);
    }

    /**
     * Display a listing of the resource.
     * @param IndexRequest $request
     * @return IndexQuickFilterCollection
     */
    public function index(IndexRequest $request)
    {
        return IndexQuickFilterCollection::make($this->quickFilterRepository->getListQuickFilters($request->all()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        return response()->json([
            'id' => $this->quickFilterRepository->createQuickFilter($request->all())->id
        ]);
    }

    /**
     * Display the specified resource.
     * @param $id
     * @return ShowQuickFilterResource
     */
    public function show($id): ShowQuickFilterResource
    {
        return ShowQuickFilterResource::make($this->quickFilterRepository->getQuickFilter($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, $id): JsonResponse
    {
        return response()->json([
            'success' => $this->quickFilterRepository->updateQuickFilter($id, $request->all())
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        return response()->json([$this->quickFilterRepository->destroyQuickFilter($id)]);
    }

    /**
     * Перечень категорий
     * @return JsonResponse
     */
    public function categories()
    {
        return response()->json($this->categoryRepository->getOptionsTree());
    }
}
