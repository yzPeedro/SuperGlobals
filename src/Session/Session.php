<?php

namespace SuperGlobals\Session;

use StdClass;

class Session
{
    private array $sessions;

    public function __construct(array $options = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->sessions = $_SESSION;

        if (isset($options['lowercase']) && $options['lowercase']) {
            foreach ($this->sessions as $item => $value) {
                if(is_string($value))
                    $this->sessions[strtolower($item)] = strtolower($value);
                else
                    $this->sessions[strtolower($item)] = $value;
            }
        }

        if (isset($options['index_lowercase']) && $options['index_lowercase']) {
            foreach ($this->sessions as $item => $value) {
                $this->sessions[strtolower($item)] = $value;
            }
        }

        if (isset($options['values_lowercase']) && $options['values_lowercase']) {
            foreach ($this->sessions as $item => $value) {
                if(is_string($value)) {
                    $this->sessions[$item] = strtolower($value);
                }
            }
        }
    }

    public function __get(string $index)
    {
        return (isset($this->sessions[$index])) ? $this->sessions[$index] : null;
    }

    public function keys(): array
    {
        return array_keys($this->sessions);
    }

    public function all($object = true): array|StdClass
    {
        if ($object)
            return json_decode(json_encode($this->sessions));

        return $this->sessions;
    }

    public function get(string|int $index): mixed
    {
        return $this->sessions[$index];
    }

    public function set(string|int $index, mixed $value): void
    {
        $_SESSION[$index] = $value;
        $this->sessions[$index] = $value;
    }

    public function clear(): void
    {
        foreach ($this->sessions as $item => $value) {
            unset($this->sessions[$item]);
            unset($_SESSION[$item]);
        }
    }

    public function remove(string $index): void
    {
        if ($this->exists($index)) {
            unset($this->sessions[$index]);
            unset($_SESSION[$index]);
        }
    }

    public function only(array $indexes, $object = true): array|StdClass
    {
        if(! $object) {
            $target = [];

            foreach ($indexes as $index) {
                if(isset($this->sessions[$index])) {
                    $target[$index] = $this->get($index);
                    continue;
                }

                $target[$index] = null;
            }

            return $target;
        }

        $target = new StdClass();

        foreach ($indexes as $index) {
            if(isset($this->sessions[$index])) {
                $target->$index = $this->get($index);
                continue;
            }

            $target->$index = null;
        }

        return $target;
    }

    public function exists(string|int $index): bool
    {
        return (isset($this->sessions[$index]));
    }
}