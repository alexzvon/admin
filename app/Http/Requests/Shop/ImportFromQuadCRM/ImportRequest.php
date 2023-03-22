<?php

namespace App\Http\Requests\Shop\ImportFromQuadCRM;

use App\Http\Controllers\Shop\QuadCRMController;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ImportRequest
 * @package App\Http\Requests\Shop\ImportFromQuadCRM
 *
 */
class ImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->guard('admin.web')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'file' => 'required|mimes:' . implode(',', QuadCRMController::AVAILABLE_FILE_MIMES),
        ];
    }

    public function getExcelFile()
    {
        return $this->file('file');
    }
}
