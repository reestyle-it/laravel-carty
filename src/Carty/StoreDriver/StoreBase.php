<?php

namespace ReeStyleIT\LaravelCarty\Carty\StoreDriver;

use ReeStyleIT\LaravelCarty\Carty;

abstract class StoreBase
{

    protected Carty $carty;

    public function __construct(Carty $carty)
    {
        $this->carty = $carty;
    }

}
