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

    App\Models\Admin::class => [
        'created' => 'Администратор #:id создан.',
        'updated' => 'Администратор #:id изменен.',
        'deleted' => 'Администратор #:id удален.',
    ],

    App\Models\Shop\FastSale\FastSale::class => [
        'created' => 'Акция стимулирования быстрой покупки создана.',
        'updated' => 'Акция стимулирования быстрой покупки изменена.',
        'deleted' => 'Акция стимулирования быстрой покупки удалена.',
    ],

    App\Models\AdminRolePermissionGroup::class => [
        'created' => 'Группа прав #:id создана.',
        'updated' => 'Группа прав #:id изменена.',
        'deleted' => 'Группа прав #:id удалена.',
    ],

    App\Models\AdminRole::class => [
        'created' => 'Роль #:id создана.',
        'updated' => 'Роль #:id изменена.',
        'deleted' => 'Роль #:id удалена.',
    ],

    App\Models\Shop\Attribute\Attribute::class => [
        'status' => [
            'enabled' => 'Аттрибут #:id показан.',
            'disabled' => 'Аттрибут #:id скрыт.',
        ],
        'created' => 'Аттрибут #:id создан.',
        'updated' => 'Аттрибут #:id изменен.',
        'deleted' => 'Аттрибут #:id удален.',
    ],

    App\Models\Shop\Category\Category::class => [
        'status' => [
            'enabled' => 'Категория #:id показана.',
            'disabled' => 'Категория #:id скрыта.',
        ],
        'created' => 'Категория #:id создана.',
        'updated' => 'Категория #:id изменена.',
        'deleted' => 'Категория #:id удалена.',
    ],

    App\Models\Shop\Room\Room::class => [
        'status' => [
            'enabled' => 'Комната #:id показана.',
            'disabled' => 'Комната #:id скрыта.',
        ],
        'created' => 'Комната #:id создана.',
        'updated' => 'Комната #:id изменена.',
        'deleted' => 'Комната #:id удалена.',
    ],

    App\Models\Shop\Style\Style::class => [
        'status' => [
            'enabled' => 'Стиль #:id показан.',
            'disabled' => 'Стиль #:id скрыт.',
        ],
        'created' => 'Стиль #:id создан.',
        'updated' => 'Стиль #:id изменен.',
        'deleted' => 'Стиль #:id удален.',
    ],

    App\Models\Shop\PriceType\PriceType::class => [
        'status' => [
            'enabled' => 'Тип цены #:id показан.',
            'disabled' => 'Тип цены #:id скрыт.',
        ],
        'created' => 'Тип цены #:id создан.',
        'updated' => 'Тип цены #:id изменен.',
        'deleted' => 'Тип цены #:id удален.',
    ],

    App\Models\Shop\Product\Product::class => [
        'status' => [
            'enabled' => 'Товар #:id показан.',
            'disabled' => 'Товар #:id скрыт.',
        ],
        'created' => 'Товар #:id создан.',
        'updated' => 'Товар #:id изменен.',
        'deleted' => 'Товар #:id удален.',
    ],

    App\Models\Shop\Supplier\Supplier::class => [
        'status' => [
            'enabled' => 'Поставщик #:id показан.',
            'disabled' => 'Поставщик #:id скрыт.',
        ],
        'created' => 'Поставщик #:id создан.',
        'updated' => 'Поставщик #:id изменен.',
        'deleted' => 'Поставщик #:id удален.',
    ],

    App\Models\Review::class => [
        'status' => [
            'enabled' => 'Отзыв #:id одобрен.',
            'disabled' => 'Отзыв #:id ожидает модерации.',
        ],
        'created' => 'Отзыв #:id создан.',
        'updated' => 'Отзыв #:id изменен.',
        'deleted' => 'Отзыв #:id удален.',
    ],

    App\Models\Shop\Promo\PromoCode::class => [
        'status' => [
            'enabled' => 'Промокод #:id задействован.',
            'disabled' => 'Промокод #:id отключен.',
        ],
        'created' => 'Промокод #:id создан.',
        'updated' => 'Промокод #:id изменен.',
        'deleted' => 'Промокод #:id удален.',
    ],

    App\Models\Shop\Badge\BadgeType::class => [
        'created' => 'Бейдж #:id создан.',
        'updated' => 'Бейдж #:id изменен.',
        'deleted' => 'Бейдж #:id удален.',
    ],

    App\Models\Shop\Banner\Banner::class => [
        'status' => [
            'enabled' => 'Баннер #:id задействован.',
            'disabled' => 'Баннер #:id отключен.',
        ],
        'created' => 'Баннер #:id создан.',
        'updated' => 'Баннер #:id изменен.',
        'deleted' => 'Баннер #:id удален.',
    ],

    App\Models\Shop\Interior\Interior::class => [
        'created' => 'Интерьер #:id создан.',
        'updated' => 'Интерьер #:id изменен.',
        'deleted' => 'Интерьер #:id удален.',
    ],

    App\Models\Shop\Sale\Sale::class => [
        'status' => [
            'enabled' => 'Акционный товар #:id задействован.',
            'disabled' => 'Акционный товар #:id отключен.',
        ],
        'created' => 'Акционный товар #:id создан.',
        'updated' => 'Акционный товар #:id изменен.',
        'deleted' => 'Акционный товар #:id удален.',
    ],

    'positions' => "Позиция сохранена.",

    'ImageEditor' => [
        'rotate' => 'Невозможно повернуть на заданный угол.',
    ]
];
