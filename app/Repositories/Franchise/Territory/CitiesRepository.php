<?php
declare(strict_types=1);

namespace App\Repositories\Franchise\Territory;

use App\Repositories\CoreRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Franchise\Territory\Cities as Model;

class CitiesRepository extends CoreRepository
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
     * @param int $id
     * @return Model|null
     */
    public function getCitiesEdit(int $id):? Model
    {
        return $this->startConditions()->with(['region', 'district', 'city', 'countryI18n'])->find($id);
    }

    /**
     * @param $input
     * @return LengthAwarePaginator
     */
    public function getListCities($input): LengthAwarePaginator
    {
        return $this->startConditions()
            ->with(['i18n', 'gmt'])
            ->orderby($input['sortBy'], $input['sortType'])
            ->paginate($input['perPage'], null, null, $input['page']);
    }

    /**
     * @param $input
     * @return Model
     */
    public function createCities($input): Model
    {
        try {
            DB::beginTransaction();

            $update = [
                'country_code' => $input['country_code'],
                'cities_id' => $input['cities_id'],
                'gmt_id' => $input['gmt_id'],
                'percent' => $input['percent'],
            ];

            if(0 !== $input['regions_id']) {
                $update['regions_id'] = $input['regions_id'];
            }

            if(0 !== $input['regions_parent_id']) {
                $update['regions_parent_id'] = $input['regions_parent_id'];
            }

            $model = $this->startConditions()->create($update);

            $model->i18n()->create([
                'language_code' => $input['language_code'],
                'title' => $input['title']
            ]);

            DB::commit();
        }
        catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }

        return $model;
    }

    /**
     * @param $id
     * @param $input
     * @return bool
     */
    public function updateCities($id, $input): bool
    {
        $result = true;

        try{
            DB::beginTransaction();

            $model = $this->startConditions()->find($id);

            if($model instanceof Model) {
                $model->update([
                    'gmt_id' => $input['gmt_id'],
                    'percent' => $input['percent'],
                    'status' => $input['status'],
                ]);
            }

            DB::commit();
        }
        catch(\Exception $exception) {
            $result = false;
            DB::rollBack();
            dd($exception);
        }

        return $result;
    }

    /**
     * @param $region_id
     * @return Collection
     */
    public function getCitiesTopRegion($region_id): Collection
    {
        return $this->startConditions()
            ->with(['i18n'])
            ->where('status', '=', true)
            ->where(function($query)use($region_id){
                $query->where('regions_id', '=', $region_id)
                    ->orWhere('regions_parent_id', '=', $region_id);
            })
            ->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getListCityNotPagination(): Collection
    {
        return $this->startConditions()->status()->with(['i18n'])->get();
    }
}
