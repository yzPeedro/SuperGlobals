<?php

namespace SuperGlobals\Globals;

use StdClass;

class Globals
{
    private array $globals;

    public function __construct(array $options = [])
    {
        $this->globals = $GLOBALS;

        if (isset($options['lowercase']) && $options['lowercase']) {
            foreach ($this->globals as $item => $value) {
                if(is_string($value))
                    $this->globals[strtolower($item)] = strtolower($value);
                else
                    $this->globals[strtolower($item)] = $value;
            }
        }

        if (isset($options['index_lowercase']) && $options['index_lowercase']) {
            foreach ($this->globals as $item => $value) {
                $this->globals[strtolower($item)] = $value;
            }
        }

        if (isset($options['values_lowercase']) && $options['values_lowercase']) {
            foreach ($this->globals as $item => $value) {
                if(is_string($value)) {
                    $this->globals[$item] = strtolower($value);
                }
            }
        }
    }

    public function __get(string $index)
    {
        return (isset($this->globals[$index])) ? $this->globals[$index] : null;
    }

    public function keys(): array
    {
        return array_keys($this->globals);
    }

    public function all($object = true): array|StdClass
    {
        if ($object)
            return json_decode(json_encode($this->globals));

        return $this->globals;
    }

    public function get(string|int $index): mixed
    {
        return $this->globals[$index];
    }

    public function set(string|int $index, mixed $value): void
    {
        $GLOBALS[$index] = $value;
        $this->globals[$index] = $value;
    }

    public function only(array $indexes, $object = true): array|StdClass
    {
        if(! $object) {
            $target = [];

            foreach ($indexes as $index) {
                if(isset($this->globals[$index])) {
                    $target[$index] = $this->get($index);
                    continue;
                }

                $target[$index] = null;
            }

            return $target;
        }

        $target = new StdClass();

        foreach ($indexes as $index) {
            if(isset($this->globals[$index])) {
                $target->$index = $this->get($index);
                continue;
            }

            $target->$index = null;
        }

        return $target;
    }

    public function exists(string|int $index): bool
    {
        return (isset($this->globals[$index]));
    }
}