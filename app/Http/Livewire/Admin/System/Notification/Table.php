<?php

namespace App\Http\Livewire\Admin\System\Notification;

use App\Http\Livewire\SendsEvents;
use App\Models\Customer\Customer;
use App\Models\Helper\Enum\NotificationType;
use App\Models\System\Notification;
use App\Models\User;
use Auth;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DatetimeColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class Table extends LivewireDatatable
{
    use SendsEvents;

    public function builder()
    {
        return Notification::query()
            ->leftJoin('users', 'notifications.resolved_by', '=','users.id');
    }

    public function columns(): array
    {
        $user = Auth::user()?->id;
        return [
            DateTimeColumn::name('notifications.created_at')
                ->label('Created')
                ->sortable()
                ->searchable(),
            Column::callback(['notifications.actor_type', 'notifications.actor_id'], static function ($type, $id) {
                if (empty($type) || empty($id)) { return "System"; }
                try {
                    $actor = ($type)::find($id);
                    if ($actor instanceof User) { return "(Admin) {$actor->name}"; }
                    if ($actor instanceof Customer) { return "(Customer) {$actor->full_name}"; }
                    return "Unknown";
                } catch (\Throwable $th) { return "Unknown"; }
            })
                ->label('Actor')
                ->sortable()
                ->searchable(),
            Column::callback(['notifications.type'], static function ($type) { return NotificationType::from($type)->label(); })
                ->label('Notification Type')
                ->filterable(NotificationType::asFilter()),
            Column::name('notifications.details')
                ->label('Details')
                ->sortable()
                ->searchable(),
            Column::callback(['notifications.subject_type', 'notifications.subject_id',], static function ($type, $id) { return ($type)::find($id)->getLink(); })
                ->label('Subject')
                ->sortable()
                ->searchable(),
            BooleanColumn::raw("(SELECT (COUNT(*) > 0) FROM seen_notifications WHERE notification_id = notifications.id AND user_id = $user) AS seen;")
                ->label('Seen')
                ->sortable()
                ->searchable(),
            Column::raw("COALESCE(users.name, 'Unresolved') AS resolved_by")
                ->label('Resolved By')
                ->sortable()
                ->searchable()
                ->filterable(User::pluck('name')->add('Unresolved')),
            Column::callback(['notifications.id', 'notifications.resolved_by'], static function ($id, $resolved) {
                return view('partials.admin.system.notification.action', ['id' => $id, 'resolved' => $resolved]);
            })
                ->label('Actions')
                ->unsortable()
                ->width('15rem'),
        ];
    }

    public function seen($id): void
    {
        $notification = Notification::find($id);
        $notification?->toggleSeen(Auth::user());
        $this->refreshTables();
    }

    public function resolve($id): void
    {
        $notification = Notification::find($id);
        $notification?->toggleResolved(Auth::user());
        $notification?->markSeen(Auth::user());
        $this->refreshTables();
    }
}
