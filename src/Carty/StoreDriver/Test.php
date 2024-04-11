<?php

namespace ReeStyleIT\LaravelCarty\Carty\StoreDriver;

use ReeStyleIT\LaravelCarty\Carty;
use ReeStyleIT\LaravelCarty\Carty\Item;
use ReeStyleIT\LaravelCarty\Contracts\CartyDriverContract;

class Test extends StoreBase implements CartyDriverContract
{

    use TestTrait;

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
