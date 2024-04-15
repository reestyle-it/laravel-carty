<?php

namespace ReeStyleIT\LaravelCarty\Carty\StoreDriver;

use ReeStyleIT\LaravelCarty\Contracts\CartyDriverContract;

class Session extends StoreBase implements CartyDriverContract
{

    private function getCarts(): array
    {
        $sessionKey = $this->carty->config()['storage']['session_key'];

        return session($sessionKey, []);
    }

    public function loadFromStore(): array
    {
        $carts = $this->getCarts();

        return $carts[$this->carty->cartId()] ?? [];
    }

    public function updateStore(array $items): void
    {
        $carts = $this->getCarts();

        $carts[$this->carty->cartId()] = $items;

        session()->put($this->carty->config()['storage']['session_key'], $carts);
    }

    public function clear(): void
    {
        session()->forget($this->carty->config()['storage']['session_key']);
    }

}
