<?php

namespace ReeStyleIT\LaravelCarty\Carty;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use ReeStyleIT\LaravelCarty\Carty;
use ReeStyleIT\LaravelCarty\Carty\Item\Options;
use ReeStyleIT\LaravelCarty\Contract\CartItemContract;

class Item implements CartItemContract
{

    use Macroable;

    protected Carty $carty;

    protected ?string $hash = null;

    protected array $itemData = [
        'id'          => null,
        'description' => null,
        'qty'         => null,
        'price'       => null,
        'tax'         => null,
    ];

    protected ?Options $options = null;

    /**
     * @var array|null[]
     * @note We SHOULD NOT store the model itself here, just a reference
     */
    protected array $modelData = [
        'idField' => null,
        'id'      => null,
        'class'   => null,
    ];

    public function __construct(Carty $carty)
    {
        $this->carty = $carty;
    }

    public function hash(): string
    {
        return $this->hash;
    }

    public function createHash(): string
    {
        $uniqueStart = dechex(time());
        $infoHashed = sha1(
            serialize(
                collect($this->itemData)
                    ->when(
                        $this->options,
                        fn(Collection $collection) => $collection->merge([
                            // Make sure option attributes to not bite basic attributes
                            'options' => $this->options->all()->toArray()
                        ])
                    )->toArray()
            )
        );

        return collect([$uniqueStart, uniqid(), $infoHashed])->join('-');
    }

    public function id(): int|string
    {
        return $this->itemData['id'];
    }

    public function quantity(): int
    {
        return $this->itemData['qty'];
    }

    public function price(bool $withTax, int $decimals = 2): string
    {
        $price = $this->itemData['price'];

        if ($withTax) {
            $price *= $this->itemData['tax'];
        }

        return number_format($price, $decimals);
    }

    public function update(int $qty): self
    {
        $this->itemData['qty'] = $qty;

        return $this;
    }

    public function increase(int $qty): self
    {
        $this->itemData['qty'] += $qty;

        return $this;
    }

    public function decrease(int $qty): self
    {
        $this->itemData['qty'] -= $qty;

        return $this;
    }

    public function addFromData(int|string $id, string $description, int $qty, float $price, int $tax = 0, ?array $options = null): self
    {
        $this->itemData = [
            'id'          => $id,
            'description' => $description,
            'qty'         => $qty,
            'price'       => $price,
            'tax'         => 1 + ($tax / 100),
        ];

        if ($options) {
            $this->options = new Options($options);
        }

        $this->hash = $this->createHash();

        return $this;
    }

    public function options(): ?Options
    {
        return $this->options;
    }

    public function setModelData(Model $model, string $idField): self
    {
        $this->modelData['idField'] = $idField;
        $this->modelData['class'] = get_class($model);

        $this->modelData['id'] = $model->{$this->modelData['idField']};

        return $this;
    }

    public function modelData(): array
    {
        return $this->modelData;
    }

    public function model(): ?Model
    {
        $modelClass = $this->modelData['class'];

        return $modelClass::find($this->modelData['id']);
    }

    public function remove(): Carty
    {
        $this->carty->removeItem($this->hash());

        return $this->carty;
    }

}
