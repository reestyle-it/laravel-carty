<?php

namespace ReeStyleIT\LaravelCarty\Carty\StoreDriver;

use ReeStyleIT\LaravelCarty\Carty;
use ReeStyleIT\LaravelCarty\Carty\Item;
use ReeStyleIT\LaravelCarty\Contracts\CartyDriverContract;

class Test extends StoreBase implements CartyDriverContract
{

    private array $carts = [];

    public bool $saved = false;

    public function testFill(array $cartContents): void
    {
        collect($cartContents)
            ->each(
                function (array $cartContent, string $cartId) {
                    $cart = new Carty($cartId, [
                        'storage' => [
                            'driver' => get_class($this)
                        ]
                    ]);

                    collect($cartContent)->each(
                        function (array $item) use ($cart) {
                            $this->carts[$cart->cartId()][] = (new Item($cart))
                                ->addFromData(
                                    $item['id'],
                                    $item['description'],
                                    $item['qty'],
                                    $item['price'],
                                    $item['tax'],
                                );
                        }
                    );
                }
            );
    }

    public function loadFromStore(): array
    {
        return $this->carts[$this->carty->cartId()];
    }

    public function updateStore(array $items): void
    {
        $this->carts[$this->carty->cartId()] = $items;

        $this->saved = true;
    }
}
