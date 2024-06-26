<?php

namespace Tests\Unit;

use ReeStyleIT\LaravelCarty\Carty;
use Tests\Support\Carty\CustomItem;
use Tests\TestBase;

class CartyTestTestDriver extends TestBase
{

    private string $driver = Carty\StoreDriver\Test::class;

    public function testConfig()
    {
        $config = [
            'storage' => ['driver' => $this->driver]
        ];

        $cart = new Carty(uniqid(), $config);

        $this->assertEquals($config, $cart->config());
    }

    public function testCartId()
    {
        $cart = new Carty($firstCartId = uniqid('first_'), [
            'storage' => ['driver' => $this->driver]
        ]);

        $this->assertEquals($firstCartId, $cart->cartId());

        // Set a new cart ID for the first cart
        $cart->cartId($newCartId = uniqid('first_new_'));

        $this->assertEquals($newCartId, $cart->cartId());

        // Duh, but still...
        $this->assertNotEquals($firstCartId, $cart->cartId());

        $secondCart = new Carty($secondCartId = uniqid('second_'), [
            'storage' => ['driver' => $this->driver]
        ]);

        $this->assertEquals($secondCartId, $secondCart->cartId());

        $this->assertNotEquals($cart->cartId(), $secondCart->cartId());
    }

    public function testItems()
    {
        $cart = new Carty($cartId = uniqid(), [
            'storage' => ['driver' => $this->driver]
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

    public function testCustomItem()
    {
        $cart = new Carty(uniqid(), [
            'storage' => ['driver' => $this->driver],
            'item'    => ['class' => CustomItem::class]
        ]);

        $cart->addItem(102, 'Some item', 10, 10, 21);

        $item = $cart->getLastUpdatedItem();

        $this->assertInstanceOf(CustomItem::class, $item);

        $this->assertHasMethod('addedFunction', $item);

        $this->assertEquals(12, $item->addedFunction());
    }

    public function testAddItem()
    {
        $cart = new Carty(uniqid(), [
            'storage' => ['driver' => $this->driver]
        ]);

        $cart->addItem(102, 'Test description', 2, 100, 21);

        $this->assertCount(1, $cart->items());

        // Yes, you can add the SAME product
        $cart->addItem(102, 'Test description', 2, 100, 21);

        $this->assertCount(2, $cart->items());
    }

    public function testLoadFromStore()
    {
        $cart = new Carty($cartId = uniqid(), [
            'storage' => ['driver' => $this->driver]
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
        $cart = new Carty(uniqid(), [
            'storage' => ['driver' => $this->driver]
        ]);

        $cart->updateStore();

        $this->assertCount(0, $cart->items());

        $cart->addItem(102, 'Test description', 2, 100, 21);

        $item = $cart->getLastUpdatedItem();

        $this->assertEquals(2, $item->quantity());

        $this->assertCount(1, $cart->items());

        $cart->updateItemById(102, 3);

        $this->assertCount(1, $cart->items());

        $this->assertEquals(3, $item->quantity());
    }

    public function testAddItemFromModel()
    {
        $this->markTestSkipped();
    }

    public function testUpdateItem()
    {
        $cart = new Carty(uniqid(), [
            'storage' => ['driver' => $this->driver]
        ]);

        $cart->updateStore();

        $this->assertCount(0, $cart->items());

        $cart->addItem(102, 'Test description', 2, 100, 21);

        $this->assertCount(1, $cart->items());

        $cart->addItem(103, 'Another test description', 2, 100, 21);

        $this->assertCount(2, $cart->items());
    }

    public function testUpdateStore()
    {
        $cart = new Carty(uniqid(), [
            'storage' => ['driver' => $this->driver]
        ]);

        $cart->updateStore();

        $this->assertCount(0, $cart->items());

        $cart->addItem(102, 'Test description', 2, 100, 21);

        /** @var Carty\StoreDriver\Test $storeDriver */
        $storeDriver = $cart->storeDriver();

        $this->assertTrue($storeDriver->saved);
    }

    public function testRemoveItemByHash()
    {
        $cart = new Carty(uniqid(), [
            'storage' => ['driver' => $this->driver]
        ]);

        $cart->updateStore();

        $this->assertCount(0, $cart->items());

        $cart->addItem(102, 'Test description', 2, 100, 21);

        $this->assertCount(1, $cart->items());

        $cart->removeItem($cart->getLastUpdatedItemHash());

        $this->assertCount(0, $cart->items());
    }

    public function testRemoveItemByItem()
    {
        $cart = new Carty(uniqid(), [
            'storage' => ['driver' => $this->driver]
        ]);

        $cart->updateStore();

        $this->assertCount(0, $cart->items());

        $cart->addItem(102, 'Test description', 2, 100, 21);

        $item = $cart->getLastUpdatedItem();

        $this->assertCount(1, $cart->items());

        // The item also has the ability to remove itself from the list
        $item->remove();

        $this->assertCount(0, $cart->items());
    }

    public function testRemoveItemByModel()
    {
        $this->markTestSkipped();
    }

    public function testRemoveItemById()
    {
        $cart = new Carty(uniqid(), [
            'storage' => ['driver' => $this->driver]
        ]);

        $cart->updateStore();

        $this->assertCount(0, $cart->items());

        $cart->addItem(102, 'Test description', 2, 100, 21);

        $this->assertCount(1, $cart->items());

        $cart->removeItemById(102);

        $this->assertCount(0, $cart->items());
    }
}
