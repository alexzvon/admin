<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiRequest extends Request
{
    public function __construct()
    {
        $this->guard = 'admin.api';
    }

    protected function failedValidation(Validator $validator)
    {
        if ($validator->fails()) {
            $errors = [];

            foreach ($validator->errors()->messages() as $fieldName => $messages) {
                $errors[$fieldName] = $messages[0];
            }
        }

        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'errors' => $errors,
        ], 422));
    }
}