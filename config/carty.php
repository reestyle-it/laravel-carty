<?php

// Default "namespace" for all configs. You may
$cartSpace = config('app.name') . '_carty';

$cartId = 'cart_contents';

return [

    'storage' => [
        // Which type of storage will be used. May also be a custom driver, e.g. to support NoSQL DB's or Redis
        'driver' => \ReeStyleIT\LaravelCarty\Carty\StoreDriver\Session::class,

        // Test driver ignore any setting.

        // Session namespace
        'session_key'   => $cartSpace,

        // Table-name for Database-driver. Cart field is config('carty.defaults.cartId')
        'table'   => $cartSpace,

        // Full path to file for File-driver
        'location' => storage_path($cartSpace),
    ],

    'defaults' => [
        // Default cart ID if no cart ID is supplied
        'cartId' => $cartId,
    ],

    'item' => [
        // Class to use for cart items
        'class' => \ReeStyleIT\LaravelCarty\Carty::class,
    ],
];
