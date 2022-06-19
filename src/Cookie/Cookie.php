<?php

namespace SuperGlobals\Cookie;

use StdClass;

class Cookie
{
    private array $cookie;

    public function __construct(array $options = [])
    {
        $this->cookie = $_COOKIE;

        if (isset($options['lowercase']) && $options['lowercase']) {
            foreach ($this->cookie as $item => $value) {
                if(is_string($value))
                    $this->cookie[strtolower($item)] = strtolower($value);
                else
                    $this->cookie[strtolower($item)] = $value;
            }
        }

        if (isset($options['index_lowercase']) && $options['index_lowercase']) {
            foreach ($this->cookie as $item => $value) {
                $this->cookie[strtolower($item)] = $value;
            }
        }

        if (isset($options['values_lowercase']) && $options['values_lowercase']) {
            foreach ($this->cookie as $item => $value) {
                if(is_string($value)) {
                    $this->cookie[$item] = strtolower($value);
                }
            }
        }
    }

    public function __get(string $index)
    {
        return (isset($this->cookie[$index])) ? $this->cookie[$index] : null;
    }

    public function keys(): array
    {
        return array_keys($this->cookie);
    }

    public function all($object = true): array|StdClass
    {
        if ($object)
            return json_decode(json_encode($this->cookie));

        return $this->cookie;
    }

    public function get(string|int $index): mixed
    {
        return $this->cookie[$index];
    }

    public function set(string|int $index, mixed $value): void
    {
        setcookie($index, $value);

        $this->cookie[$index] = $value;
    }

    public function clear(): void
    {
        foreach ($this->cookie as $item => $value) {
            unset($this->cookie[$item]);
            setcookie($item, null, -1, '/');
        }
    }

    public function remove(string $index): void
    {
        if ($this->exists($index)) {
            unset($this->cookie[$index]);
            setcookie($index, null, -1, '/');
        }
    }

    public function only(array $indexes, $object = true): array|StdClass
    {
        if(! $object) {
            $target = [];

            foreach ($indexes as $index) {
                if(isset($this->cookie[$index])) {
                    $target[$index] = $this->get($index);
                    continue;
                }

                $target[$index] = null;
            }

            return $target;
        }

        $target = new StdClass();

        foreach ($indexes as $index) {
            if(isset($this->cookie[$index])) {
                $target->$index = $this->get($index);
                continue;
            }

            $target->$index = null;
        }

        return $target;
    }

    public function exists(string|int $index): bool
    {
        return (isset($this->cookie[$index]));
    }
}