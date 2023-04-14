<?php
return [
      // 'parent_asmins' => [
    //     [
    //         'icon' => 'fas fa-users nav-icon',
    //         'route' => 'dashboard.admins.index',
    //         'title' => 'Admins',
    //         'active' => 'dashboard.admins.*',
    //         'ability' => 'admins.view',
    //     ],
    // ],
    [
        'icon' => 'nav-icon fas fa-tachometer-alt',
        'route' => 'dashboard.dashboard',
        'title' => __('Dashboard'),
        'active' => 'dashboard.dashboard',
    ],
    [
        'icon' => 'fas fa-users nav-icon',
        'route' => 'dashboard.admins.index',
        'title' => __('Admins'),
        'active' => 'dashboard.admins.*',
        'ability' => 'admins.view',
    ],
    [
        'icon' => 'fas fa-shield nav-icon',
        'route' => 'dashboard.roles.index',
        'title' => __('Roles'),
        'active' => 'dashboard.roles.*',
        'ability' => 'roles.view',
    ],
    [
        'icon' => 'fas fa-tags nav-icon',
        'route' => 'dashboard.stores.index',
        'title' => __('Stores'),
        'active' => 'dashboard.stores.*',
        'ability' => 'stores.view',
    ],
    [
        'icon' => 'fas fa-tags nav-icon',
        'route' => 'dashboard.categories.index',
        'title' => __('Categories'),
        // 'badge' => __('New'),
        'active' => 'dashboard.categories.*',
        'ability' => 'categories.view',
    ],
    [
        'icon' => 'fas fa-box nav-icon',
        'route' => 'dashboard.products.index',
        'title' => __('Products'),
        'active' => 'dashboard.products.*',
        'ability' => 'products.view',
    ],
    [
        'icon' => 'fas fa-receipt nav-icon',
        'route' => 'dashboard.orders.index',
        'title' => __('Orders'),
        'active' => 'dashboard.orders.*',
        'ability' => 'orders.view',
    ],
    [
        'icon' => 'fas fa-users nav-icon',
        'route' => 'dashboard.users.index',
        'title' => __('Users'),
        'active' => 'dashboard.users.*',
        'ability' => 'users.view',
    ],
];
