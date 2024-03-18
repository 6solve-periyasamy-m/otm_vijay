<?php

namespace App\View\Components\Livewire\Input\Select;

use App\Http\Requests\SelectFilterRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

abstract class AbstractSelectComponent extends Component
{
    protected abstract function getModels(int|null $id = null): Collection;

    protected abstract function format(Model $model): string;

    public function getAll(SelectFilterRequest $request): array
    {
        $data = [];
        foreach ($this->getModels() as $model) {
            $subData = [];
            $subData['id'] = $model->id;
            $subData['text'] = $this->format($model);
            if (str_contains(strtolower($subData['text']), strtolower($request->filter ?? ""))) $data['results'][] = $subData;
        }
        return $data;
    }

    public function getOne(int $id): array|null
    {
        $model = $this->getModels($id)->first();
        if ($model === null) return null;
        return ['id' => $model->id, 'text' => $this->format($model)];
    }
}