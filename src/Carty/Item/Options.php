<?php

namespace ReeStyleIT\Carty\Carty;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use ReeStyleIT\Carty\Carty;
use ReeStyleIT\Carty\Exceptions\CartyItemException;

class Options
{
    
    protected array $options = [];
    
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }
    
    public function all(): Collection
    {
        return collect($this->options);
    }
    
    public function get($option): Collection
    {
        return collect($this->options)->get($option);
    }
    
    public function set(string $option, mixed $value): self
    {
        $this->options[$option] = $value;
        
        return $this;
    }

}
