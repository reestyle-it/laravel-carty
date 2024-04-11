<?php

namespace ReeStyleIT\LaravelCarty\Carty\StoreDriver;

use ReeStyleIT\LaravelCarty\Carty;
use ReeStyleIT\LaravelCarty\Carty\Item;

trait TestTrait
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
}
