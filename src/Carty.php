<?php

namespace ReeStyleIT\Carty;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Traits\Macroable;
use ReeStyleIT\Carty\Carty\Item;
use ReeStyleIT\Carty\Contract\CartyContract;
use ReeStyleIT\Carty\Exceptions\CartyItemException;

class Carty implements CartyContract
{

    use Macroable;
    
    protected string $cartId; 
    
    protected array $config = [];

    protected array $items = [];

    public function __construct(?string $cartId = null, array $config = [])
    {
        $this->cartId = $cartId ?? $config['defaults']['cartId'];
        
        $this->config = $config;
    }
    
    public function setCartId(string $cartId): self
    {
        $this->cartId = $cartId;
        
        return $this;
    }

    public function cartId(): string
    {
        return $this->cartId;
    }
    
    public function loadItems(): self
    {
        $sessionItems = Session::get(config('carty.session.var'));

        return $this;
    }

    public function updateSession(): self
    {
        $items = $this->items;
        
        
        Session::put(config('carty.session.var'), );
    }

    public function config(): array
    {
        return $this->config;
    }

    public function addItem(int|string $id, string $description, int $qty): self
    {
        $item = (new Item())->addFromData($id, $description, $qty);

        $this->items[$item->hash()] = $item;

        $this->updateSession();

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

                $this->updateSession();

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

        $this->updateSession();

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

    public function removeItemById(string $hash): self
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
