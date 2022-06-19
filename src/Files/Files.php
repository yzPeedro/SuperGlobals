<?php

namespace SuperGlobals\Files;

use StdClass;

class Files
{
    private array $files;

    public function __construct(array $options = [])
    {
        $this->files = $_FILES;

        if (isset($options['lowercase']) && $options['lowercase']) {
            foreach ($this->files as $item => $value) {
                if(is_string($value))
                    $this->files[strtolower($item)] = strtolower($value);
                else
                    $this->files[strtolower($item)] = $value;
            }
        }

        if (isset($options['index_lowercase']) && $options['index_lowercase']) {
            foreach ($this->files as $item => $value) {
                $this->files[strtolower($item)] = $value;
            }
        }

        if (isset($options['values_lowercase']) && $options['values_lowercase']) {
            foreach ($this->files as $item => $value) {
                if(is_string($value)) {
                    $this->files[$item] = strtolower($value);
                }
            }
        }
    }

    public function __get(string $index)
    {
        return (isset($this->files[$index])) ? $this->files[$index] : null;
    }

    public function keys(): array
    {
        return array_keys($this->files);
    }

    public function all($object = true): array|StdClass
    {
        if ($object)
            return json_decode(json_encode($this->files));

        return $this->files;
    }

    public function get(string|int $index): mixed
    {
        return $this->files[$index];
    }

    public function set(string|int $index, mixed $value): void
    {
        $this->files[$index] = $value;
    }

    public function only(array $indexes, $object = true): array|StdClass
    {
        if(! $object) {
            $target = [];

            foreach ($indexes as $index) {
                if(isset($this->files[$index])) {
                    $target[$index] = $this->get($index);
                    continue;
                }

                $target[$index] = null;
            }

            return $target;
        }

        $target = new StdClass();

        foreach ($indexes as $index) {
            if(isset($this->files[$index])) {
                $target->$index = $this->get($index);
                continue;
            }

            $target->$index = null;
        }

        return $target;
    }

    public function exists(string|int $index): bool
    {
        return (isset($this->files[$index]));
    }
}