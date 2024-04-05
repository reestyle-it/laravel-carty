<?php

namespace ReeStyleIT\LaravelCarty\Carty\StoreDriver;

use ReeStyleIT\LaravelCarty\Contracts\CartyDriverContract;

class Session extends StoreBase implements CartyDriverContract
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

}
