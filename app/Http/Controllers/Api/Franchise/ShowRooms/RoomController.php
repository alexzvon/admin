<?php

namespace App\Http\Controllers\Api\Franchise\ShowRooms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Territory\ShowRoomRoom\CreateRequest;

use App\Http\Resources\Territory\ShowRoomRoom\ListRoomsCollection;
use App\Http\Resources\Territory\ShowRoomRoom\RoomResource;
use App\Http\Resources\MediaResource;

use App\Repositories\Franchise\ShowRooms\RoomRepository;

class RoomController extends Controller
{
    private $roomRepository;

    public function __construct()
    {
        $this->roomRepository = app(RoomRepository::class);
    }

    public function video(Request $request, $id)
    {
        return response()->json(['success' => $this->roomRepository->saveVideo($request->all(), $id)]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function imagesUpload(Request $request, $id)
    {
        return response()->json([
            'status' => 'success',
            'image' => new MediaResource($this->roomRepository->loadImageSave($request->all(), $id)),
        ]);
    }

    /**
     * @return ListRoomsCollection
     */
    public function index()
    {
        //return ListRoomsCollection::make($this->roomRepository->getListRooms());
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
     * @param CreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateRequest $request)
    {
        return response()->json($this->roomRepository->createRoom($request->all()));
    }

    /**
     * @param $group_id
     * @return ListRoomsCollection
     */
    public function show($group_id)
    {
        return ListRoomsCollection::make($this->roomRepository->getListRooms($group_id));
    }

    public function edit($id)
    {
        return RoomResource::make($this->roomRepository->getEditRoom($id));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        return response()->json(['success' => $this->roomRepository->updateShowRoom($request->all(), $id)]);
//        dd($this->roomRepository->updateShowRoom($request->all(), $id));
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
