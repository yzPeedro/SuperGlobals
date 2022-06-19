<?php

namespace SuperGlobals\Post;

use StdClass;

class Post
{
    private array $post;

    public function __construct(array $options = [])
    {
        $this->post = $_POST;

        if (isset($options['lowercase']) && $options['lowercase']) {
            foreach ($this->post as $item => $value) {
                if(is_string($value))
                    $this->post[strtolower($item)] = strtolower($value);
                else
                    $this->post[strtolower($item)] = $value;
            }
        }

        if (isset($options['index_lowercase']) && $options['index_lowercase']) {
            foreach ($this->post as $item => $value) {
                $this->post[strtolower($item)] = $value;
            }
        }

        if (isset($options['values_lowercase']) && $options['values_lowercase']) {
            foreach ($this->post as $item => $value) {
                if(is_string($value)) {
                    $this->post[$item] = strtolower($value);
                }
            }
        }
    }

    public function __get(string $index)
    {
        return (isset($this->post[$index])) ? $this->post[$index] : null;
    }

    public function keys(): array
    {
        return array_keys($this->post);
    }

    public function all($object = true): array|StdClass
    {
        if ($object)
            return json_decode(json_encode($this->post));

        return $this->post;
    }

    public function get(string|int $index): mixed
    {
        return $this->post[$index];
    }

    public function set(string|int $index, mixed $value): void
    {
        $this->post[$index] = $value;
    }

    public function only(array $indexes, $object = true): array|StdClass
    {
        if(! $object) {
            $target = [];

            foreach ($indexes as $index) {
                if(isset($this->post[$index])) {
                    $target[$index] = $this->get($index);
                    continue;
                }

                $target[$index] = null;
            }

            return $target;
        }

        $target = new StdClass();

        foreach ($indexes as $index) {
            if(isset($this->post[$index])) {
                $target->$index = $this->get($index);
                continue;
            }

            $target->$index = null;
        }

        return $target;
    }

    public function exists(string|int $index): bool
    {
        return (isset($this->post[$index]));
    }
}