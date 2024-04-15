<?php

require __DIR__ . '/../vendor/autoload.php';


app()->bind('session', Session::class);

class Session
{

    public function __construct(Illuminate\Container\Container $container)
    {

    }

    public function put(int|string|array $setting, mixed $value = null): self
    {
        if (is_array($setting)) {
            collect($setting)->each(fn ($val, $key) => $_SESSION[$key] = $val);
        } else {
            $_SESSION[$setting] = $value;
        }

        return $this;
    }

    public function add(mixed $value): self
    {
        $_SESSION[] = $value;

        return $this;
    }

    public function get(null|int|string $setting = null, mixed $default = null): mixed
    {
        return $setting ? ($_SESSION[$setting] ?? $default) : $_SESSION;
    }

    public function forget(int|string $setting): self
    {
        unset($_SESSION[$setting]);

        return $this;
    }

}
