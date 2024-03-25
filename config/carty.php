<?php

return [

    'session' => [
        'var' => config('app.name') . '_carty',
    ],

    'defaults' => [
        'cartId' => 'default-cart',
    ],

    'item' => [
        // Class to use for items
        'class' => \ReeStyleIT\Carty\Carty\Item::class,
    ],
];
