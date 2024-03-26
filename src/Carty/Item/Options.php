<?php

namespace ReeStyleIT\LaravelCarty\Carty\Item;

use Illuminate\Support\Collection;

class Options
{

    protected Collection $options;

    public function __construct(array $options = [])
    {
        $this->options = collect($options);
    }

    public function all(): Collection
    {
        return $this->options;
    }

    public function get($option): Collection
    {
        return $this->options->get($option);
    }

    public function set(string $option, mixed $value): self
    {
        $this->options->put($option, $value);

        return $this;
    }

}
