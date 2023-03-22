<?php
declare(strict_types=1);

namespace App\Repositories\Franchise\ShowRooms;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Repositories\CoreRepository;
use App\Models\Franchise\ShowRooms\Group as Model;

class GroupRepository extends CoreRepository
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
     * @param $input
     * @return Model
     */
    public function createGroupShowRoom($input): Model
    {
        try {
            DB::beginTransaction();

            $model = $this->startConditions()->create([
                'region_id' => $input['region_id'],
            ]);

            if ($model instanceof Model) {
                $model->i18n()->create([
                    'name' => $input['name'],
                    'name_vin' => $input['name_vin'],
                ]);
            }

            DB::commit();
        }
        catch (Exception $exception) {
            DB::rollBack();
            dd($exception);
        }

        return $model;
    }

    /**
     * @param $id
     * @return Model|null
     */
    public function showShowRoomGroup($id):? Model
    {
        return $this->startConditions()->with(['i18n'])->find($id);
    }

    /**
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateShowRoomGroup($id, $data): bool
    {
        $result = true;

        try {
            $model = $this->startConditions()->find($id);

            if ($model instanceof Model) {
                DB::beginTransaction();

                $model->update([
                    'region_id' => $data['region_id'],
                    'status' => $data['status']
                ]);
                $model->i18n()->update([
                    'name' => $data['name'],
                    'name_vin' => $data['name_vin']
                ]);

                DB::commit();
            }
            else {
                $result = false;
            }
        }
        catch (Exception $exception) {
            DB::rollBack();
            dd($exception);
        }

        return $result;
    }

    public function getListShowRoomGroup(): Collection
    {
        return $this->startConditions()->with(['i18n', 'region'])->get();
    }
}