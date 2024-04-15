<?php

namespace ReeStyleIT\LaravelCarty\Carty\StoreDriver;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Nette\Utils\Json;
use ReeStyleIT\LaravelCarty\Carty;
use ReeStyleIT\LaravelCarty\Contracts\CartyDriverContract;

class File extends StoreBase implements CartyDriverContract
{

    public function __construct(Carty $carty)
    {
        parent::__construct($carty);

        // Setup storage to use cart - remember that this is NOT the file itself!
        \Illuminate\Support\Facades\Storage::build([
            'driver' => 'cart',
            'root' => $this->carty->config()['storage']['location'],
        ]);
    }

    protected function getFilename(): string
    {
        // Cart file is JSON, simply named "cart_" and then its ID
        $cartId = Str::of($this->carty->cartId())->lower()->snake();

        return sprintf('cart_%s.json', $cartId);
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

    public function clear(): void
    {
        Storage::disk('cart')->delete($this->getFilename());
    }
}
