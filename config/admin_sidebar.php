<?php

return [
    [
        'name'          => 'Dashboard',
        'route'         => 'admin.home',
        'icon'          => 'm-menu__link-icon flaticon-line-graph',
        'active_routes' => ['admin.home'],
    ],
    [
        'name'          => 'Admins',
        'route'         => 'admin.admins.index',
        'icon'          => 'm-menu__link-icon flaticon-users',
        'active_routes' => ['admin.admins.index', 'admin.admins.create', 'admin.admins.edit']
    ],
    [
        'name'          => 'Users',
        'route'         => 'admin.users.index',
        'icon'          => 'm-menu__link-icon flaticon-users',
        'active_routes' => ['admin.users.index']
    ],
    [
        'name'          => 'Countries',
        'route'         => 'admin.countries.index',
        'icon'          => 'fa fa-globe',
        'active_routes' => ['admin.countries.index', 'admin.countries.create', 'admin.countries.edit']
    ],
    [
        'name'  => 'Shipments',
        'icon'  => 'fa fa-archive',
        'value' => [
              [
                'name'          => 'Shipments',
                'route'         => 'admin.shipments.index',
                'icon'          => 'fa fa-archive',
                'active_routes' => ['admin.shipments.index', 'admin.shipments.create', 'admin.shipments.edit']
              ],
              [
                'name'          => 'Payment Confirmation',
                'route'         => 'admin.shipments.payment-confirmation',
                'icon'          => 'fa fa-cc-paypal',
                'active_routes' => ['admin.shipments.payment-confirmation']
             ],
              [
                'name'          => 'Ready For Shipment',
                'route'         => 'admin.shipments.ready-for-shipment',
                'icon'          => 'fa fa-shopping-cart',
                'active_routes' => ['admin.shipments.ready-for-shipment']
             ],
              [
                'name'          => 'Delivered',
                'route'         => 'admin.shipments.ready-for-delivered',
                'icon'          => 'fa fa-truck',
                'active_routes' => ['admin.shipments.ready-for-delivered']
             ]
        ]
    ],
    [
        'name'          => 'Quotations',
        'route'         => 'admin.quotations.index',
        'icon'          => 'fa fa-dollar',
        'active_routes' => ['admin.quotations.index', 'admin.quotations.show']
    ],
    [
        'name'          => 'UK Warehouse Address',
        'route'         => 'admin.uk.warehouse.address.index',
        'icon'          => 'fa fa-map-marker',
        'active_routes' => ['admin.uk.warehouse.address.index']
    ],
//    [
//        'name'          => 'Projects',
//        'route'         => 'admin.projects.index',
//        'roles'         => ['admin', 'user'],
//        'icon'          => 'm-menu__link-icon flaticon-squares-2',
//        'active_routes' => ['admin.projects.index', 'admin.projects.create', 'admin.projects.edit']
//    ],
//    [
//        'name'          => 'Indicators',
//        'route'         => 'admin.indicators.listing',
//        'roles'         => ['user'],
//        'icon'          => 'm-menu__link-icon flaticon-line-graph',
//        'active_routes' => ['admin.indicators.listing']
//    ],
//    [
//        'name'  => 'Master',
//        'icon'  => 'subdirectory_arrow_right',
//        'value' => [
//            [
//                'name' => 'Roles',
//                'route' => 'admin.roles.index',
//                'roles' => ['roles.master'],
//                'icon' => 'm-menu__link-icon fa fa-group',
//                'active_routes' => ['admin.roles.index', 'admin.roles.create', 'admin.roles.edit'],
//            ],
//            [
//                'name' => 'Indicator Categories',
//                'route' => 'admin.indicator-categories.index',
//                'roles' => ['indicator-categories.master'],
//                'icon' => 'm-menu__link-icon fa fa-group',
//                'active_routes' => ['admin.indicator-categories.index', 'admin.indicator-categories.create', 'admin.indicator-categories.edit'],
//            ]
//        ]
//    ],
//    [
//        'name' => 'Users',
//        'route' => 'users.index',
//        'roles' => 'users.index',
//        'icon' => 'm-menu__link-bullet m-menu__link-bullet--dot',
//        'active_routes' => ['users.index', 'users.create', 'users.edit'],
//    ],
//    [
//        'name' => 'Inquiry',
//        'route' => 'inquiry.index',
//        'roles' => 'inquiry.index',
//        'icon' => 'view_module',
//        'active_routes' => ['inquiry.index'],
//    ],
//    [
//        'name' => 'Affiliations',
//        'route' => 'affiliation.index',
//        'roles' => 'affiliation.index',
//        'icon' => 'view_module',
//        'active_routes' => ['affiliation.index'],
//    ],
//    [
//        'name' => 'Career',
//        'route' => 'career.index',
//        'roles' => 'career.index',
//        'icon' => 'view_module',
//        'active_routes' => ['career.index'],
//    ],
//    [
//        'name' => 'Leads',
//        'route' => 'leads.index',
//        'roles' => 'leads.index',
//        'icon' => 'view_module',
//        'active_routes' => ['leads.index'],
//    ],
//    [
//        'name' => 'Payments',
//        'route' => 'payments.index',
//        'roles' => 'payments.index',
//        'icon' => 'view_module',
//        'active_routes' => ['payments.index'],
//    ],
//    [
//        'name' => 'Part Payments',
//        'route' => 'part-payments.index',
//        'roles' => 'part-payments.index',
//        'icon' => 'view_module',
//        'active_routes' => ['part-payments.index'],
//    ],
//    [
//        'name' => 'Packages',
//        'icon' => 'subdirectory_arrow_right',
//        'value' => [
//            [
//                'name' => 'Packages',
//                'route' => 'packages.index',
//                'roles' => 'packages.index',
//                'icon' => 'view_module',
//                'active_routes' => ['packages.index', 'packages.create', 'packages.edit'],
//            ],
//            [
//                'name' => 'Order',
//                'route' => 'packages.order',
//                'roles' => 'packages.edit',
//                'icon' => 'reorder',
//                'active_routes' => ['packages.order'],
//            ]
//        ]
//    ],
//    [
//        'name' => 'Coupons',
//        'route' => 'coupons.index',
//        'roles' => 'coupons.index',
//        'icon' => 'card_giftcard',
//        'active_routes' => ['coupons.index', 'coupons.create', 'coupons.edit'],
//    ],
//    [
//        'name' => 'Customers',
//        'route' => 'customer.index',
//        'roles' => 'customer.master',
//        'icon' => 'card_giftcard',
//        'active_routes' => ['customer.index', 'customer.create', 'customer.edit'],
//    ],
//    [
//        'name' => 'Notification Contents',
//        'route' => 'notification-contents.index',
//        'roles' => 'notification-contents.index',
//        'icon' => 'notifications',
//        'active_routes' => ['notification-contents.index', 'notification-contents.edit'],
//    ],
//    [
//        'name' => 'Share-Service',
//        'route' => 'share-service.index',
//        'roles' => '',
//        'icon' => 'share',
//        'active_routes' => ['share-service.index'],
//    ],
//    [
//        'name' => 'Master',
//        'icon' => 'subdirectory_arrow_right',
//        'value' => [
//            /*[
//                'name' => 'roless',
//                'route' => 'roless.index',
//                'roles' => 'roless.master',
//                'icon' => 'people_outline',
//                'active_routes' => ['roless.index', 'roless.create', 'roless.edit'],
//            ],*/
//            [
//                'name' => 'City',
//                'route' => 'city.index',
//                'roles' => 'city.master',
//                'icon' => 'view_module',
//                'active_routes' => ['city.index', 'city.create', 'city.edit'],
//            ],
//            [
//                'name' => 'Categories',
//                'route' => 'categories.index',
//                'roles' => 'categories.index',
//                'icon' => 'view_module',
//                'active_routes' => ['categories.index', 'categories.create', 'categories.edit'],
//            ],
//            [
//                'name' => 'Services',
//                'route' => 'services.index',
//                'roles' => 'services.index',
//                'icon' => 'view_module',
//                'active_routes' => ['services.index', 'services.create', 'services.edit'],
//            ],
//            [
//                'name' => 'Roles',
//                'route' => 'roles.index',
//                'roles' => 'roles.master',
//                'icon' => 'people_outline',
//                'active_routes' => ['roles.index', 'roles.create', 'roles.edit'],
//            ],
//            [
//                'name' => 'Lead Sorce',
//                'route' => 'lead-source.index',
//                'roles' => 'lead-source.master',
//                'icon' => 'people_outline',
//                'active_routes' => ['lead-source.index', 'lead-source.create', 'lead-source.edit'],
//            ],
//            [
//                'name' => 'Internal Cost',
//                'route' => 'internal-cost.index',
//                'roles' => 'internal-cost.master',
//                'icon' => 'people_outline',
//                'active_routes' => ['internal-cost.index', 'internal-cost.create', 'internal-cost.edit'],
//            ],
//            [
//                'name' => 'Payment Modes',
//                'route' => 'payment-mode.index',
//                'roles' => 'payment-mode.index',
//                'icon' => 'view_module',
//                'active_routes' => ['payment-mode.index', 'payment-mode.create', 'payment-mode.edit'],
//            ],
//            [
//                'name' => 'Addons',
//                'route' => 'addon.index',
//                'roles' => 'addon.index',
//                'icon' => 'view_module',
//                'active_routes' => ['addon.index', 'addon.create', 'addon.edit'],
//            ],
//        ]
//    ],
];
