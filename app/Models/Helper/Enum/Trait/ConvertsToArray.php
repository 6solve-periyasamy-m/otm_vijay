<?php

namespace App\Models\Helper\Enum\Trait;

use App\Models\Helper\Enum\OrderStatus;

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
        return ucwords(camel_to_text(strtolower(preg_replace('/[-_]/', ' ', $this->name))));
    }

    public static function asFilter(): array
    {
        $data = [];
        foreach (self::cases() as $case) {
            $data[] = ['id' => $case->value, 'name' => $case->label()];
        }
        return $data;
    }
}
