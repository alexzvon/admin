<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Api\Shop;

use App\Exceptions\AdminException;
use App\Http\Controllers\Api\ApiController;
use App\Models\Shop\Banner\BannersBlockType;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class BannerTypeController
 *
 * @package App\Http\Controllers\Api\Shop
 */
class BannerTypeController extends ApiController
{
    /**
     * @return JsonResponse
     */
    public function getBannerType(): JsonResponse
    {
        try {
            $entity = BannersBlockType::first();

            return response()->json([
                'typeId' => $entity->type,
            ]);
        }
        catch (\Throwable $e) {
            return response()->json([
                'typeId' => 0,
            ]);
        }
        catch (AdminException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
                'errors'  => $e->getParams(),
            ], 422);
        }
    }

    /**
     * @return JsonResponse
     */
    public function setBannerType(Request $request): JsonResponse
    {
        $id = $request['typeId'];

        try {
            $entity = BannersBlockType::first();

            if ($entity === null) {
                $entity = new BannersBlockType;
            }
        } catch (\Throwable $exception) {
            $entity = new BannersBlockType;
        }

        try {
            $entity->type = $id;
            $entity->save();

            return response()->json(
                [
                    'status' => 'success'
                ]
            );
        }
        catch (AdminException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
                'errors'  => $e->getParams(),
            ], 422);
        }
    }
}
