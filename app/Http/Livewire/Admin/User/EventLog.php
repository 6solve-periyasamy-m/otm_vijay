<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\Helper\Enum\ModelEventType;
use App\Models\System\ModelEvent;
use App\Models\User;
use Exception;
use Jenssegers\Agent\Agent;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DatetimeColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class EventLog extends LivewireDatatable
{
    public User $user;
    public function builder()
    {
        return ModelEvent::query()
            ->where('target_id', '=', $this->user->id)
            ->where('target_type', '=', User::class);
    }

    public function columns(): array
    {
        return [
            DatetimeColumn::name('occurred')
                ->label('Occurred')
                ->sortable()
                ->filterable(),
            Column::callback(['actor_type', 'actor_id'], function ($type, $id) {
                if (empty($type) || empty($id)) {
                    return "Not Found";
                }
                try {
                    $actor = $type::find($id);
                    return $actor?->name ?? "Not Found";
                } catch (Exception $e) { return "Not Found"; }
            })
                ->label('Actor')
                ->sortable()
                ->searchable(),
            Column::callback(['action'], function ($action) { return ModelEventType::from($action)?->label(); })
                ->label('Action')
                ->sortable()
                ->searchable(),
            Column::name('ip')
                ->label('IP Address')
                ->sortable()
                ->searchable(),
            Column::name('location')
                ->label('Location')
                ->sortable()
                ->searchable(),
            Column::callback(['agent'], function ($userAgent) {
                $agent = new Agent();
                $agent->setUserAgent($userAgent);
                return $agent->browser();
            }, [], 'agent_browser')
                ->label('Browser')
                ->sortable()
                ->searchable(),
            Column::callback(['agent'], function ($userAgent) {
                $agent = new Agent();
                $agent->setUserAgent($userAgent);
                return $agent->platform();
            }, [], 'agent_platform')
                ->label('OS')
                ->sortable()
                ->searchable(),
            BooleanColumn::callback(['agent'], function ($userAgent) {
                $agent = new Agent();
                $agent->setUserAgent($userAgent);
                return f_bool($agent->isMobile());
            }, [], 'agent_mobile')
                ->label('Mobile')
                ->sortable()
                ->searchable(),

        ];
    }
}
