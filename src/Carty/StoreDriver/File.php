<?php

namespace ReeStyleIT\LaravelCarty\Carty\StoreDriver;

use Illuminate\Support\Facades\Storage;
use Nette\Utils\Json;
use ReeStyleIT\LaravelCarty\Carty;
use ReeStyleIT\LaravelCarty\Contracts\CartyDriverContract;

class File extends StoreBase implements CartyDriverContract
{

    public function __construct(Carty $carty)
    {
        parent::__construct($carty);

//        config([''])
    }

    protected function getFilename()
    {
        return $this->carty->config()['storage']['name'];
    }

    public function loadFromStore(): array
    {
        return Json::decode(
            Storage::disk('cart')->get($this->getFilename())
        );
    }

    public function updateStore(array $items): void
    {
        Storage::disk('cart')->put(
            $this->getFilename(),
            Json::encode($this->carty->items()->toArray())
        );
    }

}
