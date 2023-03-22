<?php

namespace App\Http\Requests\Shop\Supplier;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GetRequest
 * @package App\Http\Requests\Shop\Supplier
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
            'per_page' => 'sometimes|int',
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
