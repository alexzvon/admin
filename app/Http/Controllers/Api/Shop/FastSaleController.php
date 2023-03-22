<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Shop;

use App\Exceptions\AdminException;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\UpdateFastSale;
use App\Http\Resources\Shop\FastSale\FastSaleEditResource;
use App\Http\Resources\Shop\FastSale\FastSaleTableResource;
use App\Models\Shop\FastSale\FastSale;
use Illuminate\Http\Request;

/**
 * Class FastSaleController
 *
 * @package App\Http\Controllers\Api\Shop
 */
class FastSaleController extends ApiController
{
    protected static $modelClass = FastSale::class;
    protected static $entityName = 'fast-sale';
    protected static $editResource = FastSaleEditResource::class;
    protected static $tableResource = FastSaleTableResource::class;

    public function index()
    {
        $fastSale = FastSale::firstOrCreate([], [
            'time_in_minutes'    => 10,
            'percent_of_discount' => 2,
            'is_enabled'         => true,
        ]);

        return [
            'fastSale' => static::toResource($fastSale),
        ];
    }

    public function update(UpdateFastSale $request)
    {
        try {
            $data = $request->validated();
            $fastSale = FastSale::firstOrCreate([], $data);

            if ($fastSale->getKey() === null) {
                $fastSale->save();
            } else {
                $fastSale->update($data);
            }

            return response()->json([
                'status'   => 'success',
                'message'  => $this->lang('updated', ['id' => $fastSale->id]),
                'fastSale' => static::toResource($fastSale)
            ], 200);
        }
        catch (AdminException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
                'errors'  => $e->getParams(),
            ], 422);
        }
        catch (\Exception $e) {
            //dd($e);
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
                //'message' => 'Техническая ошибка (2001). Обратитесь к разработчикам.'
            ], 500);
        }
    }
}
