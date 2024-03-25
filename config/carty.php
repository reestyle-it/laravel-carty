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
        
        // To add model directly as cart-item
        'models' => [
            \Illuminate\Foundation\Auth\User::class => [
                'idField' => 'id',
                'mapping' => [
                    
                ],
            ],
        ],
    ],
];
