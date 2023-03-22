<?php

namespace App\Http\Controllers\Api\Franchise\Territory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Franchise\Territory\CountryRepository;
use App\Repositories\Franchise\Territory\GmtRepository;
use App\Repositories\Franchise\Territory\CityRepository;

use App\Http\Requests\Territory\City\StoreRequest;
use App\Http\Requests\Territory\City\UpdateRequest;
use App\Http\Requests\Shop\QuickFilter\IndexRequest;

use App\Http\Resources\Territory\Counter\ListCollection as CounterListCollection;
use App\Http\Resources\Territory\Gmt\ListCollection as GmtListCollection;
use App\Http\Resources\Territory\City\ShowResource;
use App\Http\Resources\Territory\City\IndexCityCollection;

class CityController extends Controller
{
    private $countryRepository;
    private $gmtRepository;
    private $cityRepository;

    public function __construct()
    {
        $this->countryRepository = app(CountryRepository::class);
        $this->gmtRepository = app(GmtRepository::class);
        $this->cityRepository = app(CityRepository::class);
    }

    /**
     * @param IndexRequest $request
     * @return IndexCityCollection
     */
    public function index(IndexRequest $request)
    {
        return IndexCityCollection::make($this->cityRepository->getListCity($request->all()));
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
        return response()->json(['id' => $this->cityRepository->createCity($request->all())->id]);
    }

    /**
     * Display the specified resource.
     * @param $id
     * @return ShowResource
     */
    public function show($id)
    {
        return ShowResource::make($this->cityRepository->getCity($id));
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
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, $id)
    {
        return response()->json($this->cityRepository->updateCity($id, $request->all()));
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

    /**
     * @return CounterListCollection
     */
    public function getListCountry(): CounterListCollection
    {
        return CounterListCollection::make($this->countryRepository->getListCountry());
    }

    /**
     * @return GmtListCollection
     */
    public function getListGmt(): GmtListCollection
    {
        return GmtListCollection::make($this->gmtRepository->getListGmt());
    }
}
