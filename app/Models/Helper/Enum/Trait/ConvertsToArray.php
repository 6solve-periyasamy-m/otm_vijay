<?php

namespace App\Models\Helper\Enum\Trait;

/**
 * @method static self[] cases()
 */
trait ConvertsToArray
{
    public static function toArray(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->value] = $case->label();
        }
        return $array;
    }

    public function label(): string
    {
        return ucwords(camel_to_text(strtolower($this->name)));
    }
}
