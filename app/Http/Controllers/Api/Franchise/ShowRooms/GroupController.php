<?php

namespace App\Http\Controllers\Api\Franchise\ShowRooms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Territory\ShowRoomGroup\UpdateRequest;

use App\Http\Resources\Territory\ShowRoomGroup\ShowResource;
use App\Http\Resources\Territory\ShowRoomGroup\ListShowRoomGroupCollection;
use App\Http\Resources\Territory\ShowRoomGroup\ListRegionTopCollection;

use App\Repositories\Franchise\ShowRooms\GroupRepository;
use App\Repositories\RegionRepository;

class GroupController extends Controller
{
    private $groupRepository;
    private $regionRepository;

    public function __construct()
    {
        $this->groupRepository = app(GroupRepository::class);
        $this->regionRepository = app(RegionRepository::class);
    }

    /**
     * @return ListShowRoomGroupCollection
     */
    public function index()
    {
        return ListShowRoomGroupCollection::make($this->groupRepository->getListShowRoomGroup());
    }

    /**
     * @return ListRegionTopCollection
     */
    public function create()
    {
        return ListRegionTopCollection::make($this->regionRepository->getListRegionsTop());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return response()->json(['id' => $this->groupRepository->createGroupShowRoom($request->all())->id]);
    }

    /**
     * @param $id
     * @return ShowResource
     */
    public function show($id)
    {
        return ShowResource::make($this->groupRepository->showShowRoomGroup($id));
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
        return response()->json(['success' => $this->groupRepository->updateShowRoomGroup($id, $request->all())]);
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
