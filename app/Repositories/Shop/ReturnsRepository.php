<?php
declare(strict_types=1);

namespace App\Repositories\Shop;

use App\Repositories\CoreRepository;
use Illuminate\Support\Facades\DB;
use App\Models\Shop\ReturnRequest\ReturnRequest as Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ReturnsRepository extends CoreRepository
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
     * Загркзить таблицу
     * @param $input
     * @return LengthAwarePaginator
     */
    public function getList($input): LengthAwarePaginator
    {
        $query = $this->startConditions()
            ->setEagerLoads([])
            ->with(['product.i18n', 'status.i18n']);

        if ($input['type'] === 1 || $input['type'] === 2) {
            $query = $query->whereHas('status', function ($q) use ($input) {
                return $q->where('id', '=', $input['type']);
            });
        }

        $query = $query->orderby($input['sortBy'], $input['sortType']);
        $result = $query->paginate($input['perPage'], null, null, $input['page']);

        return $result;
    }

    /**
     * Загрузить для изменения
     * @param $input
     * @return Model
     */
    public function getEdit($input): Model
    {
        $result = $this->startConditions()
            ->setEagerLoads([])
            ->with([
                'order.orderProducts' => function ($q) use ($input) {
                    $q->where('product_id', '=', $input['product_id']);
                },
                'product.i18n',
            ])
            ->find($input['return_id']);

        $result->collection_media = $result->getMedia('attached')
            ->map(function ($media) {
                return $media->getFullUrl();
            });

        return $result;
    }

    /**
     * Сохранить изменения
     * @param $input
     * @return Boolean
     */
    public function saveEdit($input): bool
    {
        try {
            DB::beginTransaction();

            $result = $this->startConditions()
                ->where('id', '=', $input['return_id'])
                ->update([
                    'status_id' => (int)$input['status_id'],
                    'comment' => $input['comment']
                ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }

        return (bool)$result;
    }
}