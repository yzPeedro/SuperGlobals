<?php

namespace SuperGlobals\Server;

use stdClass;

class Server
{
    private array $server;

    public function __construct(array $options = [])
    {
        $this->server = $_SERVER;

        if (isset($options['lowercase']) && $options['lowercase']) {
            foreach ($this->server as $item => $value) {
                if(is_string($value))
                    $this->server[strtolower($item)] = strtolower($value);
                else
                    $this->server[strtolower($item)] = $value;
            }
        }

        if (isset($options['index_lowercase']) && $options['index_lowercase']) {
            foreach ($this->server as $item => $value) {
                $this->server[strtolower($item)] = $value;
            }
        }

        if (isset($options['values_lowercase']) && $options['values_lowercase']) {
            foreach ($this->server as $item => $value) {
                if(is_string($value)) {
                    $this->server[$item] = strtolower($value);
                }
            }
        }
    }

    public function __get(string $index)
    {
        return (isset($this->server[$index])) ? $this->server[$index] : null;
    }

    public function keys(): array
    {
        return array_keys($this->server);
    }

    public function all($object = true): array|StdClass
    {
        if ($object)
            return json_decode(json_encode($this->server));

        return $this->server;
    }

    public function get(string|int $index): mixed
    {
        return $this->server[$index];
    }

    public function set(string|int $index, mixed $value): void
    {
        $_SERVER[$index] = $value;
        $this->server[$index] = $value;
    }

    public function remove(string|int $index): void
    {
        if($this->exists($index)) {
            unset($_SERVER[$index]);
            unset($this->server[$index]);
        }
    }

    public function only(array $indexes, $object = true): array|StdClass
    {
        if(! $object) {
            $target = [];

            foreach ($indexes as $index) {
                if(isset($this->server[$index])) {
                    $target[$index] = $this->get($index);
                    continue;
                }

                $target[$index] = null;
            }

            return $target;
        }

        $target = new StdClass();

        foreach ($indexes as $index) {
            if(isset($this->server[$index])) {
                $target->$index = $this->get($index);
                continue;
            }

            $target->$index = null;
        }

        return $target;
    }

    public function exists(string|int $index): bool
    {
        return (isset($this->server[$index]));
    }
}