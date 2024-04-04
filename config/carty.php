<?php

return [

    'session' => [
        'store' => \ReeStyleIT\LaravelCarty\Carty\StoreDriver\Session::class,
        'var' => config('app.name') . '_carty',
    ],

    'defaults' => [
        'cartId' => 'default-cart',
    ],

    'item' => [
        // Class to use for items
        'class' => \ReeStyleIT\LaravelCarty\Carty::class,
    ],
];
