<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    App\Models\Product::class => [
        'status' => [
            'enabled' => 'Product :id enabled.',
            'disabled' => 'Product :id disabled.',
        ],
    ],

    App\Models\Category::class => [
        'status' => [
            'enabled' => 'Category :id enabled.',
            'disabled' => 'Category :id disabled.',
        ],
    ],
];