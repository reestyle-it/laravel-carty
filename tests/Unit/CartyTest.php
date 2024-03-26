<?php

namespace Tests\Unit;

use ReeStyleIT\LaravelCarty\Carty;
use Tests\TestBase;

class CartyTest extends TestBase
{

    public function testConfig()
    {
        $config = [
            'session' => ['store' => Carty\StoreDriver\Test::class]
        ];

        $cart = new Carty(uniqid(), $config);

        $this->assertEquals($config, $cart->config());
    }

    public function testCartId()
    {
        $cart = new Carty($cartId = uniqid(), [
            'session' => ['store' => Carty\StoreDriver\Test::class]
        ]);

        $this->assertEquals($cartId, $cart->cartId());

        $cart->cartId($newCartId = uniqid());

        $this->assertEquals($newCartId, $cart->cartId());
    }

    public function testItems()
    {
        $cart = new Carty($cartId = uniqid(), [
            'session' => ['store' => Carty\StoreDriver\Test::class]
        ]);

        /** @var Carty\StoreDriver\Test $storeDriver */
        $storeDriver = $cart->storeDriver();

        $this->assertCount(0, $cart->items());


        $items = [
            [
                'id'          => 102,
                'description' => 'Some item',
                'qty'         => 10,
                'price'       => 10,
                'tax'         => 21,
            ]
        ];
        $storeDriver->testFill([
            $cartId => $items
        ]);

        $cart->loadFromStore();

        $this->assertCount(1, $cart->items());

        $item = (new Carty\Item($cart))->addFromData(102, 'Some item', 10, 10, 21);

        $this->assertEquals(10.00, $item->price(false));
        $this->assertEquals(12.10, $item->price(true));
    }

    public function testAddItem()
    {
        $cart = new Carty(uniqid(), [
            'session' => ['store' => Carty\StoreDriver\Test::class]
        ]);

        $cart->addItem(102, 'Test description', 2, 100, 21);

        $this->assertCount(1, $cart->items());
    }

    public function testLoadFromStore()
    {
        $cart = new Carty($cartId = uniqid(), [
            'session' => ['store' => Carty\StoreDriver\Test::class]
        ]);

        /** @var Carty\StoreDriver\Test $storeDriver */
        $storeDriver = $cart->storeDriver();

        $this->assertCount(0, $cart->items());

        $items = [
            $item = [
                'id'          => 102,
                'description' => 'Some item',
                'qty'         => 10,
                'price'       => 10,
                'tax'         => 10,
            ]
        ];
        $storeDriver->testFill([
            $cartId => $items
        ]);

        $cart->loadFromStore();

        $this->assertCount(1, $cart->items());
    }

    public function testUpdateItemById()
    {
        $this->markTestSkipped();
    }

    public function testAddItemFromModel()
    {
        $this->markTestSkipped();
    }

    public function testUpdateItem()
    {
        $this->markTestSkipped();
    }

    public function testUpdateStore()
    {
        $cart = new Carty(uniqid(), [
            'session' => ['store' => Carty\StoreDriver\Test::class]
        ]);

        $cart->updateStore();

        $this->assertCount(0, $cart->items());

        $cart->addItem(102, 'Test description', 2, 100, 21);

        /** @var Carty\StoreDriver\Test $storeDriver */
        $storeDriver = $cart->storeDriver();

        $this->assertTrue($storeDriver->saved);
    }

    public function testRemoveItem()
    {
        $this->markTestSkipped();
    }

    public function testRemoveItemByModel()
    {
        $this->markTestSkipped();
    }

    public function testRemoveItemById()
    {
        $this->markTestSkipped();
    }
}
