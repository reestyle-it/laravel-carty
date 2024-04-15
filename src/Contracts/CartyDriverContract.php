<?php

namespace ReeStyleIT\LaravelCarty\Contracts;

interface CartyDriverContract
{

    public function loadFromStore(): array;

    public function updateStore(array $items): void;

    public function clear(): void;

}
