<?php

namespace App\Http\Requests\Shop\QuadCRMTime;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GetRequest
 * @package App\Http\Requests\Shop\QuadCRMTime
 *
 */
class GetRequest extends FormRequest
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
            //
        ];
    }

    /**
     * @return int|null
     */
    public function getPerPage(): ?int
    {
        return $this->get('per_page');
    }
}
