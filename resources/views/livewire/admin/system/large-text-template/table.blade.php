<div>
    @foreach(\App\Models\System\LargeTextTemplate::all() as $template)
        <x-admin.section.accordion closed>
            <x-slot:title>{{ $template->name }} | {{ $template->type->label() }} @if($template->default) | Default @endif</x-slot:title>
            <x-admin.section.card>
                <div class="row">
                    <div class="col-10">
                        {{ $template->description  }}</div>
                    <div class="col-1">
                        <a class="btn btn-primary" href="{{ route('settings.template.form', ['template' => $template,]) }}">
                            {{ Icon::edit() }} Edit
                        </a>
                    </div>
                    <div class="col-1">
                        <button class="btn btn-danger" wire:click="delete({{$template->id}})">
                            {{ Icon::delete() }} Delete
                        </button>
                    </div>
                </div>
            </x-admin.section.card>
            <x-admin.section.card>
                {!! $template->content !!}
            </x-admin.section.card>
        </x-admin.section.accordion>
    @endforeach
</div>
