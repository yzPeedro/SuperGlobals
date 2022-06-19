<?php

namespace SuperGlobals\Get;

use stdClass;

class Get
{
    private array $get;

    public function __construct(array $options = [])
    {
        $this->get = $_GET;

        if (isset($options['lowercase']) && $options['lowercase']) {
            foreach ($this->get as $item => $value) {
                if(is_string($value))
                    $this->get[strtolower($item)] = strtolower($value);
                else
                    $this->get[strtolower($item)] = $value;
            }
        }

        if (isset($options['index_lowercase']) && $options['index_lowercase']) {
            foreach ($this->get as $item => $value) {
                $this->get[strtolower($item)] = $value;
            }
        }

        if (isset($options['values_lowercase']) && $options['values_lowercase']) {
            foreach ($this->get as $item => $value) {
                if(is_string($value)) {
                    $this->get[$item] = strtolower($value);
                }
            }
        }
    }

    public function __get(string $index)
    {
        return (isset($this->get[$index])) ? $this->get[$index] : null;
    }

    public function keys(): array
    {
        return array_keys($this->get);
    }

    public function all($object = true): array|StdClass
    {
        if ($object)
            return json_decode(json_encode($this->get));

        return $this->get;
    }

    public function get(string|int $index): mixed
    {
        return $this->get[$index];
    }

    public function set(string|int $index, mixed $value): void
    {
        $this->get[$index] = $value;
    }

    public function only(array $indexes, $object = true): array|StdClass
    {
        if(! $object) {
            $target = [];

            foreach ($indexes as $index) {
                if(isset($this->get[$index])) {
                    $target[$index] = $this->get($index);
                    continue;
                }

                $target[$index] = null;
            }

            return $target;
        }

        $target = new StdClass();

        foreach ($indexes as $index) {
            if(isset($this->get[$index])) {
                $target->$index = $this->get($index);
                continue;
            }

            $target->$index = null;
        }

        return $target;
    }

    public function exists(string|int $index): bool
    {
        return (isset($this->get[$index]));
    }
}