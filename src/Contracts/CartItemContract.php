<?php

namespace ReeStyleIT\LaravelCarty\Contracts;

use Illuminate\Database\Eloquent\Model;
use ReeStyleIT\LaravelCarty\Carty;
use ReeStyleIT\LaravelCarty\Carty\Item\Options;

interface CartItemContract
{

    public function hash(): string;

    public function update(int $qty): self;

    public function id(): int|string;

    public function quantity(): int;

    public function increase(int $qty): self;

    public function decrease(int $qty): self;

    public function addFromData(int|string $id, string $description, int $qty, float $price, int $tax = 0, ?array $options = null): self;

    public function options(): ?Options;

    public function setModelData(Model $model, string $idField): self;

    public function modelData(): array;

    public function model(): ?Model;

    public function remove(): Carty;
}
