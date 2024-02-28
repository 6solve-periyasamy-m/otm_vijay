<?php

namespace App\Report;

use Illuminate\Database\Query\Builder;
use Livewire\Wireable;
use Mediconesystems\LivewireDatatables\Column;

abstract class BespokeReport implements Wireable
{
    protected readonly array $columns;

    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    /**
     * The query required for building the
     * @return Builder
     */
    public abstract function getQuery();

    /**
     * Get the requested list of columns for the report
     * @return Column[]
     */
    public function getColumns(): array
    {
        $columns = [];
        $colDefs = $this->allColumns();
        foreach ($this->columns as $column) {
            $col = ($colDefs[$column] ?? null)?->column;
            if ($col !== null) $columns[] = $col;
        }
        return $columns;
    }

    /**
     * @return ColumnDefinition[]
     */
    protected abstract function allColumns(): array;

    public function toLivewire(): array
    {
        return ['columns' => $this->columns,];
    }

    public static function fromLivewire($value): static
    {
        return new static($value);
    }
}
