<?php

namespace ReeStyleIT\LaravelCarty\Contract;

interface CartyDriverContract
{

    public function loadFromStore(): array;

    public function updateStore(array $items): void;

}
