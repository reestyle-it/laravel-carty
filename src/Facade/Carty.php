<?php

namespace ReeStyleIT\Carty\Facade;

use Illuminate\Support\Facades\Facade;
use ReeStyleIT\Carty\Contract\CartyContract;

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
