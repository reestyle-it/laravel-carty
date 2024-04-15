<?php

namespace Tests\Unit\StoreDriver;

use ReeStyleIT\LaravelCarty\Carty;
use ReeStyleIT\LaravelCarty\Carty\StoreDriver\File;
use Tests\TestBase;

class FileTest extends TestBase
{

    public function testLoad()
    {
        $cart = new Carty(uniqid('first_'), [
            'storage' => [
                'driver'      => File::class,
                'location' => 'my_key',
            ]
        ]);

        $fileDriver = new File($cart);

        $fileData = ['my_key' => [
            $cart->cartId() =>
                $items = [
                    [
                        'id'          => 102,
                        'description' => 'Some item',
                        'qty'         => 10,
                        'price'       => 10,
                        'tax'         => 21,
                    ]
                ]
        ]];

        $sessionItems = $fileDriver->loadFromStore();

        self::assertEquals($items, $sessionItems);
    }

    public function testUpdate()
    {

        $cart = new Carty(uniqid('first_'), [
            'storage' => [
                'driver'      => File::class,
                'session_key' => 'my_key',
            ]
        ]);

        $sessionDriver = new File($cart);

        $items = [
            [
                'id'          => 102,
                'description' => 'Some item',
                'qty'         => 10,
                'price'       => 10,
                'tax'         => 21,
            ]
        ];

        $fileDriver->updateStore($items);

        $sessionItems = $fileDriver->loadFromStore();

        self::assertEquals($items, $sessionItems);
    }

}
