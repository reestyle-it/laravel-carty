<?php

namespace Tests\Support\Carty;

use ReeStyleIT\LaravelCarty\Carty\Item;

class CustomItem extends Item
{

    public function addedFunction(): string
    {
        return __LINE__;
    }

}
