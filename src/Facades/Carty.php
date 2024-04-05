<?php

namespace ReeStyleIT\LaravelCarty\Facades;

use Illuminate\Support\Facades\Facade;
use ReeStyleIT\LaravelCarty\Contracts\CartyContract;

/**
 * @see \ReeStyleIT\Carty\Carty
 */
class Carty extends Facade
{

    public static function getFacadeAccessor(): string
    {
        return CartyContract::class;
    }

}
