<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ImageUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // todo: разобраться с правами на загрузку изображений
    // По сути надо дождаться повторения ситуации и тогда решить что делать.

    protected function failedValidation(Validator $validator)
    {
        return response()->json([
            'status' => 'error',
            'message' => $validator->messages()->first('file')
        ], 422);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'bail|file|image|mimes:jpeg,jpg,png|max:8000',
        ];
    }
}