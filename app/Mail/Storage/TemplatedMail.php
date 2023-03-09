<?php

namespace App\Mail\Storage;

use App\Mail\TemplatedMailable;
use Faker\Factory as Faker;
use Faker\Generator;

abstract class TemplatedMail
{
    protected string|null $code;
    protected Generator $faker;

    public function __construct(string|null $code = null)
    {
        $this->code = $code;
        $this->faker = Faker::create();
    }

    public abstract function getShortcodes($model = null): array;

    public function getBody($model = null): string
    {
        if ($this->code === null) return "";
        return $this->replaceShortcodes(setting("email.{$this->code}.template"), $this->getShortcodes($model));
    }

    public function getSubject($model = null): string
    {
        if ($this->code === null) return "";
        return $this->replaceShortcodes(setting("email.{$this->code}.subject"), $this->getShortcodes($model));
    }

    public function getTemplatedMailable($model = null): TemplatedMailable
    {
        return new TemplatedMailable($this->getSubject($model), $this->getBody($model));
    }

    protected final function replaceShortcodes(string $body, array $shortcodes): string
    {
        $replacement = $body;
        foreach ($shortcodes as $key => $value) {
            $replacement = str_replace('[' . $key . ']', $value, $replacement);
        }
        return $replacement;
    }
}
