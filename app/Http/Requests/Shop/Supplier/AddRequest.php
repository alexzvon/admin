<?php

namespace App\Http\Requests\Shop\Supplier;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AddRequest
 * @package App\Http\Requests\Shop\Supplier
 *
 */
class AddRequest extends FormRequest
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
     * @return int
     */
    public function getId(): int
    {
        return $this->route()->parameter('id');
    }
}
