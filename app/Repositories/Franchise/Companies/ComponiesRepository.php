<?php
declare(strict_types=1);

namespace App\Repositories\Franchise\Companies;

use App\Models\Franchise\Companies\WorkingDay;
use App\Repositories\CoreRepository;
use App\Models\Franchise\Companies\Companies as Model;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ComponiesRepository extends CoreRepository
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
     * Создать компанию
     * @param $input
     * @return Model|null
     */
    public function createCompanies($input):? Model
    {
        DB::beginTransaction();

        try {
            $model = $this->startConditions()
                ->create([
                    'user_id' => $input['user'],
                    'name' => $input['name'],
                    'delivery_address' => $input['delivery_address'],
                    'city_id' => $input['cities'],
                    'address' => $input['address'],
                    'phone' => $input['phone'],
                    'email' => $input['email'],
                    'bank_name' => $input['bank'],
                    'bank_identification_code' => $input['bik'],
                    'bank_correspondent_account' => $input['kor_account'],
                    'taxpayer_identification_number' => $input['tin'],
                    'tax_registration_reason_code' => $input['trrc'],
                    'account' => $input['rach_account'],
                    'show_cashback' => false,
                ]);

            $model->i18n()->create(['title' => $input['fio']]);

            $model->addMedia($input['file'])->toMediaCollection('franchise', 's3');

            $newWorkingDay = new WorkingDay([
                'franchise_company_id' => $model->id,
                'finished_at' => null,
                'day' => Carbon::today(),
            ]);

            $newWorkingDay->save();

            $model->workingDays()->save($newWorkingDay);

            $model->save();

            DB::commit();
        }
        catch(\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }

        return $model;
    }

    /**
     * Изменить компанию
     * @param $id
     * @param $input
     * @return bool
     */
    public function updateCompanies($id, $input): bool
    {
        $result = true;

        DB::beginTransaction();

        try{
            $model = $this->startConditions()->with(['i18n'])->find($id);

            $model->update([
                'user_id' => $input['input_user'],
                'city_id' => $input['input_cities'],
                'name' => $input['input_name'],
                'delivery_address' => $input['input_delivery_address'],
                'status' => $input['input_status'],
                'address' => $input['input_address'],
                'phone' => $input['input_phone'],
                'email' => $input['input_email'],
                'bank_name' => $input['input_bank'],
                'bank_identification_code' => $input['input_bik'],
                'bank_correspondent_account' => $input['input_kor_account'],
                'taxpayer_identification_number' => $input['input_tin'],
                'tax_registration_reason_code' => $input['input_trrc'],
                'account' => $input['input_rach_account'],
                'retail_id' => $input['input_retail_crm_id'],
            ]);

            $model->i18n()->update([
                'title' => $input['input_fio']
            ]);

            if (0 < count($input['input_income'])) {
                foreach($input['input_income'] as $item) {
                    $income[$item['id']] = [ 'percent' => $item['percent'], 'type_income' => $item['type']];
                }

                $model->income()->sync($income);
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
     * Загрузить партнера
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Model[]|null
     */
    public function getCompany($id):? Model
    {
        return $this->startConditions()
            ->with(['cities' => function($query){
                return $query->status();
            }, 'income.i18n'])
            ->find($id);
    }

    /**
     * спасок компании
     * @param $input
     * @return LengthAwarePaginator
     */
    public function getListCompanies($input): LengthAwarePaginator
    {
        return $this->startConditions()
            ->with(['i18n', 'cities.i18n'])
            ->orderby($input['sortBy'], $input['sortType'])
            ->paginate($input['perPage'], null, null, $input['page']);
    }

    /**
     * @param $id
     * @return bool
     */
    public function destroyCompanies($id): bool
    {
        $result = true;

        DB::beginTransaction();

        try{
            $model = $this->startConditions()->with(['i18n'])->find($id);

            $model->i18n[0]->delete();

            $model->delete();

            DB::commit();
        }
        catch(\Exception $exception) {
            $result = false;
            DB::rollBack();
            dd($exception);
        }

        return $result;
    }
}
