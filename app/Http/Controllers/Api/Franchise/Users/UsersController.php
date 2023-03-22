<?php

namespace App\Http\Controllers\Api\Franchise\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Franchise\Users\GetListFranchiseUsersRequest;
use App\Http\Requests\Franchise\Users\DelFranchiseUserRequest;
use App\Repositories\Franchise\Users\UsersRepository;
use App\Http\Resources\Franchise\Users\GetListFranchiseUsersCollection;

class UsersController extends Controller
{
    protected $usersRepository;

    public function __construct()
    {
        $this->usersRepository = app(UsersRepository::class);
    }

    /**
     * Перечень пользователей
     * @param  GetListFranchiseUsersRequest  $request
     * @return GetListFranchiseUsersCollection
     */
     public function getListFranchiseUsers(GetListFranchiseUsersRequest $request)
     {
        return GetListFranchiseUsersCollection::make($this->usersRepository->getListFranchiseUsers($request->all()));
     }

     public function delFranchiseUser(DelFranchiseUserRequest $request)
     {
         return response()->json(['data' => ['success' => $this->usersRepository->delFranchiseUser($request->user_id)]]);
     }

    /**
     * Пользователь
     * @param  int  $idUser
     * @return \Illuminate\Http\JsonResponse
     */
     public function getFranchiseUser(int $idUser)
     {
         return response()->json([ 'data' => $this->usersRepository->getFranchiseUser($idUser) ]);
     }

    /**
     * Изменить пользователя
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
     public function updateFranchiseUser(Request $request)
     {
        return response()->json(['data' => ['success' => $this->usersRepository->updateFranchiseUser($request->all())]]);
     }

    /**
     * Создать пользователя
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
     public function createFranchiseUser(Request $request)
     {
        return response()->json(['data' => $this->usersRepository->createFranchiseUser($request->all())]);
     }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
     public function getListFranchiseUsersNotCompanies(int $idUser)
     {
         return response()->json(['data' => $this->usersRepository->getListFranchiseUsersNotCompanies($idUser)]);
     }
}
