<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Collection;

class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $request;
    protected static $modelClass;
    protected static $entityName;
    protected static $editResource;
    protected static $tableResource;


    public function __construct(ApiRequest $request)
    {
        $this->request = $request;

        if (! \Auth::guard('admin.api')->check()) {
            throw new HttpResponseException(response()->json([
                'status' => 'error',
                'redirect' => '/login',
            ], 401));
        }
    }

    public function getModel($id)
    {
        return static::$modelClass::findOrFail($id);
    }

    protected function lang($identif, $params = null, $useModelClass = true)
    {
        return \Lang::get(
            "admin." . ($useModelClass ? static::$modelClass . '.' : '') . $identif,
            $params ?: []
        );
    }

    /**
     * Данные сущности.
     *
     * @param Integer $id
     * @return array
     */
    public function show($id)
    {
        $entityName = static::$entityName;

        return [
            "{$entityName}" => static::toResource(static::getModel($id)),
        ];
    }

    /**
     * Преобразование сущности в ресурс.
     *
     * @param array $entity
     * @return array
     */
    protected static function toResource($entity = [])
    {
        if ($entity instanceof Collection) {
            return static::$tableResource::collection($entity);
        }

        return new static::$editResource($entity);
    }
}