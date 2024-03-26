<?php

namespace ReeStyleIT\LaravelCarty\Facades;

use Illuminate\Support\Facades\Facade;
use ReeStyleIT\LaravelCarty\Contract\CartyContract;

/**
 * @see \ReeStyleIT\Carty\Carty
 */
class Carty extends Facade
{

    public function getFacadeAccessor()
    {
        return CartyContract::class;
    }

}
