<?php

namespace App\Http\Controllers\Api\Franchise\Territory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Territory\Cities\StoreRequest;
use App\Http\Requests\Shop\QuickFilter\IndexRequest;
use App\Http\Requests\Territory\Cities\UpdateRequest;

use App\Http\Resources\Territory\Cities\ListCitiesCollection;
use App\Http\Resources\Territory\Cities\SearchCitiesCollection;
use App\Http\Resources\Territory\Cities\ShowResource;
use App\Http\Resources\Territory\Cities\ListCitiesTopRegionCollection;

use App\Repositories\Franchise\Territory\CitiesRepository;
use App\Repositories\CityRepository;

class CitiesController extends Controller
{
    private $citiesRepository;
    private $cityRepository;

    public function __construct()
    {
        $this->citiesRepository = app(CitiesRepository::class);
        $this->cityRepository = app(CityRepository::class);
    }

    public function search(Request $request)
    {
        return SearchCitiesCollection::make($this->cityRepository->search($request->q));
    }

    /**
     * @param $region_id
     * @return ListCitiesTopRegionCollection
     */
    public function getListCitiesTopRegion($region_id)
    {
        return ListCitiesTopRegionCollection::make($this->citiesRepository->getCitiesTopRegion($region_id));
    }

    /**
     * @param IndexRequest $request
     * @return ListCitiesCollection
     */
    public function index(IndexRequest $request)
    {
        return ListCitiesCollection::make($this->citiesRepository->getListCities($request->all()));
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
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        return response()->json(['id' => $this->citiesRepository->createCities($request->all())->id]);
    }

    /**
     * @param $id
     * @return ShowResource
     */
    public function show($id)
    {
        return ShowResource::make($this->citiesRepository->getCitiesEdit($id));
//        dd($this->citiesRepository->getCitiesEdit($id));
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
     */
    public function update(UpdateRequest $request, $id)
    {
        return response()->json($this->citiesRepository->updateCities($id, $request->all()));
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
