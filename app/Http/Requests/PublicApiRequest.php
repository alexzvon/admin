<?php

declare(strict_types=1);

namespace App\Http\Requests;

/**
 * Class PublicApiRequest
 *
 * @package App\Http\Requests
 */
class PublicApiRequest extends ApiRequest
{

    /**
     * PublicApiRequest constructor.
     */
    public function __construct()
    {
        $this->guard = null;
    }

    /**
     * Проверка прав пользователя на совершение текущего действия.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
