<?php

namespace ReeStyleIT\Carty\Carty;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use ReeStyleIT\Carty\Carty;
use ReeStyleIT\Carty\Exceptions\CartyItemException;

class Item
{

    use Macroable;

    protected Carty $carty;

    protected ?string $hash = null;

    protected array $itemData = [
        'id'          => null,
        'description' => null,
        'qty'         => null,
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
        return sha1(
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
    }

    public function update(int $qty): self
    {
        $this->itemData['qty'] = $qty;

        return $this;
    }

    public function quantity(): int
    {
        return $this->itemData['qty'];
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

    public function addFromData(int|string $id, string $description, int $qty, ?array $options = null): self
    {
        $this->itemData = [
            'id'          => null,
            'description' => null,
            'qty'         => null,
        ];

        if ($options) {
            $options = new Options($options);
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

    public function getModelData(): array
    {
        return $this->modelData;
    }

}
