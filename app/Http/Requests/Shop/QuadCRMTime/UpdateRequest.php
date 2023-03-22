<?php

namespace App\Http\Requests\Shop\QuadCRMTime;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\Shop\QuadCRMTime
 *
 */
class UpdateRequest extends FormRequest
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
            'time' => 'required'
        ];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->route()->parameter('id');
    }

    /**
     * @return string
     */
    public function getTime(): string
    {
        return $this->get('time');
    }
}
