<?php

namespace SuperGlobals\Env;

use stdClass;

class Env
{
    private array $env;

    public function __construct(array $options = [])
    {
        $this->env = $_ENV;

        if (isset($options['lowercase']) && $options['lowercase']) {
            foreach ($this->env as $item => $value) {
                if(is_string($value))
                    $this->env[strtolower($item)] = strtolower($value);
                else
                    $this->env[strtolower($item)] = $value;
            }
        }

        if (isset($options['index_lowercase']) && $options['index_lowercase']) {
            foreach ($this->env as $item => $value) {
                $this->env[strtolower($item)] = $value;
            }
        }

        if (isset($options['values_lowercase']) && $options['values_lowercase']) {
            foreach ($this->env as $item => $value) {
                if(is_string($value)) {
                    $this->env[$item] = strtolower($value);
                }
            }
        }
    }

    public function __get(string $index)
    {
        return (isset($this->env[$index])) ? $this->env[$index] : null;
    }

    public function keys(): array
    {
        return array_keys($this->env);
    }

    public function all($object = true): array|StdClass
    {
        if ($object)
            return json_decode(json_encode($this->env));

        return $this->env;
    }

    public function get(string|int $index): mixed
    {
        return $this->env[$index];
    }

    public function set(string|int $index, mixed $value): void
    {
        $_ENV[$index] = $value;
        $this->env[$index] = $value;
    }

    public function only(array $indexes, $object = true): array|StdClass
    {
        if(! $object) {
            $target = [];

            foreach ($indexes as $index) {
                if(isset($this->env[$index])) {
                    $target[$index] = $this->get($index);
                    continue;
                }

                $target[$index] = null;
            }

            return $target;
        }

        $target = new StdClass();

        foreach ($indexes as $index) {
            if(isset($this->env[$index])) {
                $target->$index = $this->get($index);
                continue;
            }

            $target->$index = null;
        }

        return $target;
    }

    public function exists(string|int $index): bool
    {
        return (isset($this->env[$index]));
    }
}