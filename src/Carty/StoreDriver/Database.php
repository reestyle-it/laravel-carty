<?php

namespace ReeStyleIT\LaravelCarty\Carty\StoreDriver;

use ReeStyleIT\LaravelCarty\Contracts\CartyDriverContract;

class Database extends StoreBase implements CartyDriverContract
{

    public function loadFromStore(): array
    {
        // TODO: Implement loadFromStore() method.
        return [];
    }

    public function updateStore(array $items): void
    {
        // TODO: Implement updateStore() method.
    }

    public function clear(): void
    {
        // TODO: Implement clear() method.
    }
}
