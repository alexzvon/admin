<?php
declare(strict_types=1);

namespace App\Repositories\Franchise\Users;

use App\Models\Franchise\Users\Users as Model;
use App\Repositories\CoreRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;

class UsersRepository extends CoreRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getModelClass(): string
    {
        return Model::class;
    }

    /**
     * @param $idUser
     * @return Model|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Model[]
     */
    public function getFranchiseUser($idUser): Model
    {
        return $this->startConditions()
            ->with(['franchise_companies'])
            ->where('is_franchisee', true)
            ->where('is_designer', false)
            ->find($idUser);
    }

    /**
     * @param $input
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getListFranchiseUsers($input): LengthAwarePaginator
    {
        return $this->startConditions()
            ->with(['franchise_companies.i18n'])
            ->where('is_franchisee', true)
            ->where('is_designer', false)
            ->orderby($input['sortBy'], $input['sortType'])
            ->paginate($input['perPage'], null, null, $input['page']);
    }

    /**
     * @return array
     */
    public function getListFranchiseUsersNotCompanies($idUser): array
    {
        $input = [
            'sortBy' => 'id',
            'sortType' => 'asc',
            'perPage' => '100',
            'page' => '1',
        ];

        $users = $this->getListFranchiseUsers($input);

        foreach ($users as $user) {
            if (is_null($user->franchise_companies) || $idUser === $user->id) {
                $list[] = [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                ];
            }
        }

        return $list;
    }

    /**
     * @param $idUser
     * @return bool
     */
    public function delFranchiseUser($idUser): bool
    {
        $result = 0;

        $model = $this->getFranchiseUser($idUser);

        if (is_null($model->franchise_companies)) {
            try {
                $result = $model->delete();
            } catch (\Exception $exception) {
                dd($exception->getMessage());
            }
        }

        return $result;
    }

    /**
     * @param $input
     * @return int|null
     */
    public function updateFranchiseUser($input): ?int
    {
        $result = null;

        try {
            DB::beginTransaction();

            if ('' !== $input['password']) {
                if ($input['password'] === $input['confirm_password']) {
                    $result = $this->startConditions()
                        ->where('id', $input['user_id'])
                        ->update([
                                     'first_name' => $input['first_name'],
                                     'last_name' => $input['last_name'],
                                     'phone' => $input['phone'],
                                     'email' => $input['email'],
                                     'password' => Hash::make($input['password']),
                                 ]);
                } else {
                    $result = 0;
                }
            } else {
                $result = $this->startConditions()
                    ->where('id', $input['user_id'])
                    ->update([
                                 'first_name' => $input['first_name'],
                                 'last_name' => $input['last_name'],
                                 'phone' => $input['phone'],
                                 'email' => $input['email'],
                             ]);
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception->getMessage());
        }

        return $result;
    }

    /**
     * @param $input
     * @return Model|null
     */
    public function createFranchiseUser($input): ?Model
    {
        $result = null;

        if ($input['password'] === $input['confirm_password']) {
            try {
                $result = $this->startConditions()
                    ->create([
                                 'first_name' => $input['first_name'],
                                 'last_name' => $input['last_name'],
                                 'phone' => $input['phone'],
                                 'email' => $input['email'],
                                 'password' => Hash::make($input['password']),
                                 'is_franchisee' => $input['is_franchisee'],
                                 'is_designer' => $input['is_designer'],
                                 'email_verified_at' => now(),
                             ]);
            } catch (\Exception $exception) {
                dd($exception->getMessage());
            }
        }

        return $result;
    }
}
