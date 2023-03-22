<?php

namespace App\Http\Controllers\Api\Franchise\Companies;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Companies\StoreRequest;
use App\Http\Requests\Companies\UpdateRequest;
use App\Http\Requests\Shop\QuickFilter\IndexRequest;

use App\Repositories\Franchise\Territory\CitiesRepository;
use App\Repositories\Franchise\UsersRepository;
use App\Repositories\Franchise\Companies\ComponiesRepository;
use App\Repositories\Franchise\Income\IncomeRepository;

use App\Http\Resources\Territory\City\CityNotPagenationCollection;
use App\Http\Resources\Companies\ShowResource;
use App\Http\Resources\Income\IncomeNotPagenationCollection;
use App\Http\Resources\Companies\IndexCollection;

class CompaniesController extends Controller
{
    private $citiesRepository;
    private $usersRepository;
    private $componiesRepository;
    private $incomeRepository;

    public function __construct()
    {
        $this->citiesRepository = app(CitiesRepository::class);
        $this->usersRepository = app(UsersRepository::class);
        $this->componiesRepository = app(ComponiesRepository::class);
        $this->incomeRepository = app(IncomeRepository::class);
    }

    /**
     * Display a listing of the resource.
     * @param IndexRequest $request
     * @return IndexCollection
     */
    public function index(IndexRequest $request)
    {
        return IndexCollection::make($this->componiesRepository->getListCompanies($request->all()));
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
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        return response()->json(['id' => $this->componiesRepository->createCompanies($request->all())->id]);
    }


    /**
     * Display the specified resource.
     * @param $id
     * @return ShowResource
     */
    public function show($id)
    {
        return ShowResource::make($this->componiesRepository->getCompany($id));
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
     * @param UpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function update(UpdateRequest $request, $id)
    {
        return response()->json([$this->componiesRepository->updateCompanies($id, $request->all())]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return response()->json([$this->componiesRepository->destroyCompanies($id)]);
    }

    /**
     * @return CityNotPagenationCollection
     */
    public function cityNotPagination()
    {
        return CityNotPagenationCollection::make($this->citiesRepository->getListCityNotPagination());
    }

    /**
     * @return IncomeNotPagenationCollection
     */
    public function incomeNotPagination()
    {
        return IncomeNotPagenationCollection::make($this->incomeRepository->getListIncomeNotPagination());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListUsersForFilter()
    {
        return response()->json([ 'users' => $this->usersRepository->getListUsers()]);
    }
}
