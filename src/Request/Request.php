<?php

namespace SuperGlobals\Request;

use stdClass;

class Request
{
    private array $request;

    public function __construct(array $options = [])
    {
        $this->request = $_REQUEST;

        if (isset($options['lowercase']) && $options['lowercase']) {
            foreach ($this->request as $item => $value) {
                if(is_string($value))
                    $this->request[strtolower($item)] = strtolower($value);
                else
                    $this->request[strtolower($item)] = $value;
            }
        }

        if (isset($options['index_lowercase']) && $options['index_lowercase']) {
            foreach ($this->request as $item => $value) {
                $this->request[strtolower($item)] = $value;
            }
        }

        if (isset($options['values_lowercase']) && $options['values_lowercase']) {
            foreach ($this->request as $item => $value) {
                if(is_string($value)) {
                    $this->request[$item] = strtolower($value);
                }
            }
        }
    }

    public function __get(string $index)
    {
        return (isset($this->request[$index])) ? $this->request[$index] : null;
    }

    public function keys(): array
    {
        return array_keys($this->request);
    }

    public function all($object = true): array|StdClass
    {
        if ($object)
            return json_decode(json_encode($this->request));

        return $this->request;
    }

    public function get(string|int $index): mixed
    {
        return $this->request[$index];
    }

    public function set(string|int $index, mixed $value): void
    {
        $this->request[$index] = $value;
    }

    public function only(array $indexes, $object = true): array|StdClass
    {
        if(! $object) {
            $target = [];

            foreach ($indexes as $index) {
                if(isset($this->request[$index])) {
                    $target[$index] = $this->get($index);
                    continue;
                }

                $target[$index] = null;
            }

            return $target;
        }

        $target = new StdClass();

        foreach ($indexes as $index) {
            if(isset($this->request[$index])) {
                $target->$index = $this->get($index);
                continue;
            }

            $target->$index = null;
        }

        return $target;
    }

    public function exists(string|int $index): bool
    {
        return (isset($this->request[$index]));
    }
}