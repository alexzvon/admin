<?php
declare(strict_types=1);

namespace App\Repositories\Franchise\Income;

use App\Repositories\CoreRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Models\Franchise\Income\Income as Model;
use Illuminate\Database\Eloquent\Collection;

class IncomeRepository extends CoreRepository
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
     *
     * @param $input
     * @return int
     */
    public function createIncome($input): int
    {
        DB::beginTransaction();
        try{
            $model = $this->startConditions()
                ->create([
                    'percent' => $input['percent'],
                    'type' => $input['type'],
                ]);

            $model->i18n()->create([
                'title' => $input['name']
            ]);

            DB::commit();
        }
        catch(\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }

        return $model->id;
    }

    /**
     * @param $id
     * @return Model|null
     */
    public function getIncome($id):? Model
    {
        return $this->startConditions()->with(['i18n'])->find($id);
    }

    /**
     * @param $id
     * @param $input
     * @return bool
     */
    public function updateIncome($id, $input): bool
    {
        $result = true;

        DB::beginTransaction();

        try{
            $model = $this->startConditions()->with(['i18n'])->find($id);

            if ($model instanceof Model) {
                $model->update([
                    'percent' => $input['percent'],
                    'status' => $input['status'],
                    'type' => $input['type'],
                ]);

                $model->i18n[0]->update([
                    'title' => $input['name']
                ]);

                DB::commit();
            }
            else {
                $result = false;
                DB::rollBack();
            }
        }
        catch(\Exception $exception) {
            $result = false;
            DB::rollBack();
            dd($exception);
        }

        return $result;
    }

    /**
     * @param $input
     * @return LengthAwarePaginator
     */
    public function getListIncome($input): LengthAwarePaginator
    {
        return $this->startConditions()
            ->status()
            ->with(['i18n'])
            ->orderby($input['sortBy'], $input['sortType'])
            ->paginate($input['perPage'], null, null, $input['page']);
    }

    /**
     * @return Collection
     */
    public function getListIncomeNotPagination(): Collection
    {
        return $this->startConditions()
            ->status()
            ->with(['i18n'])
            ->get();
    }
}