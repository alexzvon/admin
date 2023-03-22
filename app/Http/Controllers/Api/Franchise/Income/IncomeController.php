<?php

namespace App\Http\Controllers\Api\Franchise\Income;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Income\StoreRequest;
use App\Http\Requests\Shop\QuickFilter\IndexRequest;

use App\Repositories\Franchise\Income\IncomeRepository;

use App\Http\Resources\Income\ShowResource;
use App\Http\Resources\Income\IndexCollection;

class IncomeController extends Controller
{
    private $incomeRepository;

    public function __construct()
    {
        $this->incomeRepository = app(IncomeRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexRequest $request
     * @return IndexCollection
     */
    public function index(IndexRequest $request)
    {
        return IndexCollection::make($this->incomeRepository->getListIncome($request->all()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        return response()->json(['id' => $this->incomeRepository->createIncome($request->all())]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return ShowResource
     */
    public function show($id)
    {
        return ShowResource::make($this->incomeRepository->getIncome($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        return response()->json($this->incomeRepository->updateIncome($id, $request->all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
