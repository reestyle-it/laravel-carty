<?php

namespace ReeStyleIT\LaravelCarty;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use ReeStyleIT\LaravelCarty\Carty\Item;
use ReeStyleIT\LaravelCarty\Contract\CartyContract;
use ReeStyleIT\LaravelCarty\Contract\CartyDriverContract;
use ReeStyleIT\LaravelCarty\Exceptions\CartyItemException;

class Carty implements CartyContract
{

    use Macroable;

    protected string $cartId;

    protected array $config = [];

    protected array $items = [];

    protected CartyDriverContract $storeDriver;

    public function __construct(?string $cartId = null, array $config = [])
    {
        $this->cartId = $cartId ?? $config['defaults']['cartId'];

        $this->config = $config;

        $sessionStoreClassName = $config['session']['store'];

        $this->storeDriver = new $sessionStoreClassName($this);
    }

    public function cartId(?string $cartId = null): self|string
    {
        if ($cartId) {
            $this->cartId = $cartId;

            $return = $this;
        } else {
            $return = $this->cartId;
        }

        return $return;
    }

    public function storeDriver(): CartyDriverContract
    {
        return $this->storeDriver;
    }

    public function loadFromStore(): self
    {
        $this->items = $this->storeDriver->loadFromStore();

        return $this;
    }

    public function updateStore(): self
    {
        $this->storeDriver->updateStore($this->items);

        return $this;
    }

    public function clearStore(): self
    {
        $this->items = [];

        $this->storeDriver->updateStore([]);

        return $this;
    }

    public function config(): array
    {
        return $this->config;
    }

    public function addItem(int|string $id, string $description, int $qty, float $price, int $tax): self
    {
        $item = (new Item($this))->addFromData($id, $description, $qty, $price, $tax);

        $this->items[$item->hash()] = $item;

        $this->updateStore();

        return $this;
    }

    public function addItemFromModel(Model $model): self
    {
        $config = $this->config();

        if (isset($config['model-mapping'])) {
            if (isset($config['model-mapping'][get_class($model)])) {
                $mapping = $config['model-mapping'][get_class($model)];

                $newItem = new Item();

                collect($mapping)->each(
                    fn ($destination, $source) => $newItem->{$destination} = $model->{$source}
                );

                $model->setModelData($model, $config);

                $this->items[$newItem->hash()] = $newItem;

                $this->updateStore();

            } else {
                throw new CartyItemException(
                    sprintf('Model "%s" defined in "%s".', get_class($model), config_path('carty.php'))
                );
            }
        } else {
            throw new CartyItemException(
                sprintf('No models defined in "%s".', config_path('carty.php'))
            );
        }

        return $this;
    }

    public function updateItem(string $hash, int $qty): self
    {
        /** @var Item $item */
        $item = $this->items()->get($hash);

        $item->update($qty);

        $this->updateStore();

        return $this;
    }

    public function updateItemById(int|string $id, int $qty): self
    {
        /** @var Item $item */
        $item = $this->items()
            ->filter(
                fn (array $item) => $item['id'] === $id
            )
            ->first();

        return $this->updateItem($item->hash());
    }

    public function removeItem(string $hash): self
    {
        $this->items = $this->items()->forget($hash)->toArray();
    }

    public function removeItemById(int|string $id): self
    {
        /** @var Item $item */
        $item = $this->items()
            ->filter(
                fn (array $item) => $item['id'] === $id
            )
            ->first();

        return $this->removeItem($item->hash());
    }

    public function removeItemByModel(Model $model): self
    {
        /** @var Item $item */
        $item = $this->items()
            ->filter(
                fn (array $item) => $item['id'] === $model->id
            )
            ->first();

        return $this->removeItem($item->hash());
    }

    /**
     * @return Collection|Item[]
     */
    public function items(): Collection
    {
        return collect($this->items);
    }

}
