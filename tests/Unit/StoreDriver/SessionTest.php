<?php

namespace Tests\Unit\StoreDriver;

use ReeStyleIT\LaravelCarty\Carty;
use ReeStyleIT\LaravelCarty\Carty\StoreDriver\Session;
use Tests\TestBase;

class SessionTest extends TestBase
{

    public function testLoad()
    {
        $cart = new Carty(uniqid('first_'), [
            'storage' => [
                'driver'      => Session::class,
                'session_key' => 'my_key',
            ]
        ]);

        $sessionDriver = new Session($cart);

        $sessionData = ['my_key' => [
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
        session($sessionData);

        $sessionItems = $sessionDriver->loadFromStore();

        self::assertEquals($items, $sessionItems);
    }

    public function testUpdate()
    {

        $cart = new Carty(uniqid('first_'), [
            'storage' => [
                'driver'      => Session::class,
                'session_key' => 'my_key',
            ]
        ]);

        $sessionDriver = new Session($cart);

        $items = [
            [
                'id'          => 102,
                'description' => 'Some item',
                'qty'         => 10,
                'price'       => 10,
                'tax'         => 21,
            ]
        ];

        $sessionDriver->updateStore($items);

        $sessionItems = $sessionDriver->loadFromStore();

        self::assertEquals($items, $sessionItems);
    }

    public function testClear()
    {

        $cart = new Carty(uniqid('first_'), [
            'storage' => [
                'driver'      => Session::class,
                'session_key' => 'my_key',
            ]
        ]);

        $sessionDriver = new Session($cart);

        $items = [
            [
                'id'          => 102,
                'description' => 'Some item',
                'qty'         => 10,
                'price'       => 10,
                'tax'         => 21,
            ]
        ];

        $sessionDriver->updateStore($items);

        self::assertCount(1, $sessionDriver->loadFromStore());

        $sessionDriver->clear();

        self::assertCount(0, $sessionDriver->loadFromStore());
    }

}
