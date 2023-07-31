<?php

namespace App\Repository\Intention\Storage;

abstract class IntentionAction
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public abstract function process(): bool;

    public function json(): string
    {
        return json_encode($this->data);
    }

    public function array(): array
    {
        return $this->data;
    }
}
