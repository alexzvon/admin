<?php
declare(strict_types=1);

namespace App\Repositories\Shop;

use App\Repositories\CoreRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Shop\QuickFilter\QuickFilter as Model;

class QuickFilterRepository extends CoreRepository
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
     * Измеить быстрый фильтр
     * @param $id
     * @param $input
     * @return bool
     */
    public function updateQuickFilter($id, $input): bool
    {
        $result = true;

        DB::beginTransaction();
        try {
            $model = $this->startConditions()
                ->with(['i18n'])
                ->find($id);

            $result = $model->update([
                    'category_id' => $input['category'],
                    'adress' => $input['address'],
                    'slug' => $input['slug'],
                    'status' => $input['status']
                ]);

            $result = $model->i18n[0]->update([
                'title' => $input['name'],
                'displayed_name' => $input['displayed_name'],
            ]);

            if (isset($input['file'])) {
                $model->clearMediaCollectionExcept('adm', $model->getFirstMedia());
                $model->addMedia($input['file'])->toMediaCollection('adm', 's3');
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
     * Создать быстрый фильтр
     * @param $input
     * @return Model
     */
    public function createQuickFilter($input): Model
    {
        DB::beginTransaction();

        try {
            $model = $this->startConditions()
                ->create([
                    'category_id' => $input['category'],
                    'adress' => $input['address'],
                    'slug' => $input['slug']
                ]);

            $model->i18n()
                ->create([
                    'title' => $input['name'],
                    'displayed_name' => $input['displayed_name'],
                ]);

            $model->addMedia($input['file'])->toMediaCollection('adm', 's3');

            DB::commit();
        }
        catch(\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }

        return $model;
    }

    /**
     * Загрузить модель для редактирования
     * @param $id
     * @return Model|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Model[]
     */
    public function getQuickFilter($id): Model
    {
        return $this->startConditions()
            ->with(['i18n', 'media'])
            ->find($id);
    }

    /**
     * Загрузить список
     * @param $input
     * @return LengthAwarePaginator
     */
    public function getListQuickFilters($input): LengthAwarePaginator
    {
        $result = $this->startConditions()
            ->with(['i18n', 'category.i18n'])
            ->orderby($input['sortBy'], $input['sortType'])
            ->paginate($input['perPage'], null, null, $input['page']);

        return $result;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function destroyQuickFilter($id): bool
    {
        $result = true;
        DB::beginTransaction();

        try {
            $model = $this->startConditions()->with(['i18n'])->find($id);
            $model->i18n[0]->delete();
            $model->delete();

            DB::commit();
        } catch(\Exception $exception) {
            $result = false;
            DB::rollBack();
            dd($exception);
        }

        return $result;
    }
}