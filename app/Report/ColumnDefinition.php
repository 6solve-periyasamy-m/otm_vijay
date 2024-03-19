<?php

namespace App\Report;

use Mediconesystems\LivewireDatatables\Column;

class ColumnDefinition
{
    public string $name;
    public string $description;
    public Column $column;

    public function __construct(string $key, Column $column)
    {
        $this->name = __($key . '.name');
        $this->description = __($key . '.description');
        $this->column = $column->label($this->name)->sortable()->searchable();
    }
}
