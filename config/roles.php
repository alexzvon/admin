<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Нерпикасаемые роли
    |--------------------------------------------------------------------------
    |
    | Список id ролей, которые не могут быть изменены через
    | административную панель.
    |
    */

    'untouchableIds' => [
        1, 2
    ],

    /*
    |--------------------------------------------------------------------------
    | ID суперадмина
    |--------------------------------------------------------------------------
    |
    | ID пользователя, права которого не нуждаются в подтверждении.
    |
    */

    'superAdminId' => env('SUPER_ADMIN_ID', 1)
];