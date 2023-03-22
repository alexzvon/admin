<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Api;

use App\Imports\CountRows;
use App\Imports\ProductsImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

/**
 * Class ImportController
 *
 * @package App\Http\Controllers\Api
 */
class ImportController extends ApiController
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function checkSchema(Request $request): JsonResponse
    {
        /** @var UploadedFile $file */
        $file = $request->file('importFile');
        // @s3 $path = $request->file('importFile')->storeAs('imports', $file->getClientOriginalName());
        $path = $request->file('importFile')->storeAs('imports', $file->getClientOriginalName(),'s3');

        HeadingRowFormatter::default('none');
        $headings = (new HeadingRowImport)->toArray($path);
        $hash = hash('md5', implode(',', $headings[0][0]));
        $schemaHash = hash('md5', implode(',', \ProductsMovements::headings()));

        $importObject = new CountRows;
        HeadingRowFormatter::default('none');
        Excel::import($importObject, $file);


        return response()->json([
            'status' => 'success',
            'data'   => [
                'is_equal' => $hash === $schemaHash,
                'total'    => $importObject->count,
            ],
        ], 200);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function import(Request $request): JsonResponse
    {
        ini_set('max_execution_time', '14400');
        /** @var UploadedFile $file */
        $file = $request->file('importFile');

        $importObject = new ProductsImport;
        HeadingRowFormatter::default('none');
        Excel::import($importObject, $file);

        return response()->json([
            'status' => 'success',
            'data'   => [
                'imported' => $importObject->data
            ],
        ], 200);
    }
}
