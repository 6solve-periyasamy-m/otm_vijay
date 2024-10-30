<?php

namespace App\Http\Livewire\Abstract;

use App\View\Components\DisplayModeComponent;
use Mediconesystems\LivewireDatatables\Column;

class DisplayModeColumn extends Column
{
    public static function table(string $table, string $content = 'name', string $type = 'display_mode_type', string $color = 'display_mode_color'): self
    {
        return parent::callback(
            [
                "$table.$content",
                "$table.$type",
                "$table.$color",
            ],
            static function ($content, $type, $color) {
                if (empty($content)) {
                    return "";
                }
                return (new DisplayModeComponent($content, $type, $color))->render();
        })->sortBy("$table.$content")->filterOn("$table.$content");
    }
}
