<?php

namespace App\Repository\Traits;

trait HasCustomAttributes
{
    public function __get(string $name): mixed
    {
        $pName = snake_to_pascal($name);
        $method = "get{$pName}Attribute";
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }
        return null;
    }

    public function __set(string $name, mixed $value): void
    {
        $pName = snake_to_pascal($name);
        $method = "set{$pName}Attribute";
        if (method_exists($this, $method)) {
            $this->{$method}();
        }
    }
}
