<?php

return [

    'storage' => [
        // Which type of storage will be used. May also be a custom driver, e.g. to support NoSQL DB's or Redis
        'driver' => \ReeStyleIT\LaravelCarty\Carty\StoreDriver\Session::class,

        // Use the following setting for:
        // - Full file to path for File-driver
        // - (custom) table-name for Database-driver
        // -
        //
        // Test driver ignore this setting.
        'name'   => config('app.name') . '_carty',
    ],

    'defaults' => [
        // Default cart ID if no cart ID is supplied
        'cartId' => 'default-cart',
    ],

    'item' => [
        // Class to use for cart items
        'class' => \ReeStyleIT\LaravelCarty\Carty::class,
    ],
];
