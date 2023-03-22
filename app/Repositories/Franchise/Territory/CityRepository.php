<?php
declare(strict_types=1);

namespace App\Repositories\Franchise\Territory;

use App\Repositories\CoreRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Franchise\Territory\City as Model;
use Illuminate\Database\Eloquent\Collection;

class CityRepository extends CoreRepository
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
     * Создать город
     * @param $input
     * @return Model
     */
    public function createCity($input): Model
    {
        DB::beginTransaction();
        try {
            $model = $this->startConditions()
                ->create([
                    'country_id' => $input['country_id'],
                    'gmt_id' => $input['gmt_id'],
                    'percent' => $input['percent']
                ]);

            $model->i18n()->create([
                'title' => $input['name'],
            ]);

            DB::commit();
        }
        catch(\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }

        return $model;
    }

    /**
     * Загрузить город
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Model[]|null
     */
    public function getCity($id):? Model
    {
        return $this->startConditions()->with(['i18n'])->find($id);
    }

    /**
     * Изменить город
     * @param $id
     * @param $input
     * @return bool
     */
    public function updateCity($id, $input): bool
    {
        $result = true;

        DB::beginTransaction();
        try {
            $model = $this->startConditions()
                ->with(['i18n'])
                ->find($id);

            if ($model instanceof Model) {
                $model->update([
                    'country_id' => $input['country_id'],
                    'gmt_id' => $input['gmt_id'],
                    'percent' => $input['percent'],
                    'status' => $input['status'],
                ]);

                $model->i18n[0]->update([
                    'title' => $input['name']
                ]);

                DB::commit();
            }
            else {
                DB::rollBack();
                $result = false;
            }
        }
        catch (\Exception $exception) {
            DB::rollBack();
            $result = false;
            dd($exception);
        }

        return $result;
    }

    /**
     * Загрузить список
     * @param $input
     * @return LengthAwarePaginator
     */
    public function getListCity($input): LengthAwarePaginator
    {
        $result = $this->startConditions()
            ->with(['i18n', 'country.i18n', 'gmt'])
            ->orderby($input['sortBy'], $input['sortType'])
            ->paginate($input['perPage'], null, null, $input['page']);

        return $result;
    }

    /**
     * @return Collection
     */
    public function getListCityNotPagination(): Collection
    {
        return $this->startConditions()->status()->with(['i18n'])->get();
    }
}