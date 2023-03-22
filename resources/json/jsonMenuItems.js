import requestHandler from '../js/core/apiRequest';

// module.exports =
export default
[
  {
    title: 'QuadCRM',
    icon: 'fa fa-shopping-bag',
    children: [
      {
        title: 'Suppliers',
        url: '/suppliers',
        icon: 'gi gi-user',
        permission: 'system.admins.menu',
      },
      {
        title: 'Time for export',
        url: '/time/for-export',
        icon: 'gi gi-clock',
        permission: 'system.admins.menu',
      },
      {
        title: 'Time for import',
        url: '/time/for-import',
        icon: 'gi gi-clock',
        permission: 'system.admins.menu',
      },
      {
        title: 'Save QuadDB_out.xlsx',
        url: '/save-QuadDB_out',
        icon: 'gi gi-disk_save',
        permission: 'system.admins.menu',
      },
      {
        title: 'Import excel file',
        url: '/import-from-QuadCRM-show',
        icon: 'gi gi-disk_import',
        permission: 'system.admins.menu',
      },
    ],
  },

  {
    title: 'Магазин',
    icon: 'fa fa-shopping-bag',
    children: [
      {
        title: 'Товары',
        url: '/shop/products',
        icon: 'gi gi-shopping_bag',
        permission: 'shop.products.menu',
      },
      {
        title: 'Загрузить Товары',
        url: '/loader/products',
        icon: 'gi gi-shopping_bag',
        permission: 'shop.products.menu',
      },
      {
        title: 'Категории',
        url: '/shop/categories',
        icon: 'fa fa-folder',
        permission: 'shop.categories.menu',
      },
      {
        title: 'Быстрые фильтры',
        url: '/shop/quick-filter/table',
        icon: 'fa fa-camera',
        permission: 'shop.categories.menu',
      },
      {
        title: 'Комнаты',
        url: '/shop/rooms',
        icon: 'fa fa-bath',
        permission: 'shop.rooms.menu',
      },

      {
        title: 'Стили',
        url: '/shop/styles',
        icon: 'fa fa-home',
        permission: 'shop.styles.menu',
      },

      {
        title: 'Поставщики',
        url: '/shop/suppliers',
        icon: 'fa fa-truck',
        permission: 'shop.suppliers.menu',
      },

      {
        title: 'Аттрибуты',
        url: '/shop/attributes',
        icon: 'fa fa-list',
        permission: 'shop.attributes.menu',
      },

      {
        title: 'Типы цен',
        url: '/shop/price-types',
        icon: 'fa fa-money',
        permission: 'shop.price-types.menu',
      },

      {
        title: 'Акции и промо',
        icon: 'fa fa-star',
        permission: 'shop.promo.menu',
        children: [
          {
            title: 'Баннеры',
            icon: 'fa fa-image',
            permission: 'shop.banners.menu',
            url: '/shop/banners',
          },
          {
            title: 'Тип рекламного блока',
            icon: 'fa fa-image',
            permission: 'shop.banners.menu',
            url: '/shop/banners-types',
          },
          {
            title: 'Акционные товары',
            url: '/shop/sale',
            icon: 'fa fa-percent',
            permission: 'shop.sale.menu',
          },
          {
            title: 'Стимулирование быстрой покупки',
            url: '/shop/fast-sale',
            icon: 'fa fa-percent',
            permission: 'shop.sale.menu',
          },
          {
            title: 'Промокоды',
            url: '/shop/promo-codes',
            icon: 'fa fa-ticket',
            permission: 'shop.promo-codes.menu',
          },
          {
            title: 'Всплывающий баннер',
            url: '/shop/expand-banners',
            icon: 'fa fa-window-restore',
            permission: 'shop.expand-banners.menu',
          },
        ],
      },
      {
        title: 'Меню',
        url: '/shop/menus',
        icon: 'fa fa-compass',
        permission: 'shop.menus.menu',
      },
      {
        title: 'Бейджи',
        url: '/shop/badge-types',
        icon: 'fa fa-tag',
        permission: 'shop.badge-types.menu',
      },
      {
        title: 'Интерьеры',
        url: '/shop/interiors',
        icon: 'fa fa-hotel',
        permission: 'shop.interiors.menu',
      },
      {
        title: 'Покупатели',
        url: '/shop/customers',
        icon: 'gi gi-parents',
        permission: 'shop.customers.menu',
      },
      {
        title: 'Возврат',
        url: '/shop/reply/table',
        icon: 'fa fa-reply',
        permission: 'shop.suppliers.edit',
      },
    ],
  },
  {
    title: 'Франшиза',
    icon: 'fa fa-retweet',
    permission: 'reviews.menu',

    children: [
      {
        title: 'Пользователи',
        url: '/franchise/users/table',
        icon: 'fa fa-users',
        permission: 'system.admins.menu',
      },
      {
        title: 'Партнеры',
        url: '/companies/table',
        icon: 'fa fa-users',
        permission: 'system.admins.menu',
      },
      {
        title: 'Города присутствия',
        url: '/cities/table',
        icon: 'fa fa-id-card-o',
        permission: 'system.admins.menu',
      },
      {
        title: 'Шоурум',
        url: '/showroomgroup/table/',
        icon: 'fa fa-krw',
        permission: 'system.admins.menu',
      },
      {
        title: 'Источники дохода',
        url: '/income/table',
        icon: 'fa fa-krw',
        permission: 'system.admins.menu',
      },
    ],
  },
  {
    title: 'Отзывы',
    url: '/reviews',
    icon: 'fa fa-comment',
    permission: 'reviews.menu',
  },
  {
    title: 'Контент',
    url: '/contents',
    icon: 'fa fa-font',
    permission: 'contents.menu',
  },
  {
    title: 'Система',
    icon: 'fa fa-gears',
    permission: 'system.menu',

    children: [
      {
        title: 'Администраторы',
        url: '/system/admins',
        icon: 'fa fa-id-card-o',
        permission: 'system.admins.menu',
      },

      {
        title: 'Контроль доступа',
        icon: 'fa fa-ban',

        children: [
          {
            title: 'Роли',
            url: '/system/rbac/roles',
            icon: 'fa fa-group',
            permission: 'system.rbac.roles.menu',
          },

          {
            title: 'Права',
            url: '/system/rbac/permission-groups',
            icon: 'fa fa-star',
            permission: 'system.rbac.permission-groups.menu',
          },
        ],
      },

      {
        title: 'Настройки',
        url: '/system/rbac/settings',
        icon: 'fa fa-gear',
        permission: 'system.settings.menu',
      },
    ],
  },

  {
    title: 'Очистить кэш',
    onClick() {
      new requestHandler('get', `/api/cache`).start();
    },
    icon: 'fa fa-refresh',
  },
  {
    title: 'Test',
    icon: 'fa fa-check',

    children: [
      {
        title: 'Адрес',
        url: '/test/address/',
        icon: 'fa fa-address-card',
      },
      {
        title: 'Товар',
        url: '/test/product/',
        icon: 'fa fa-address-card',
      },
    ],
  },
];
