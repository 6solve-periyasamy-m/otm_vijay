<div>
    @foreach(\App\Models\System\LargeTextTemplate::all() as $template)
        <x-admin.section.accordion closed>
            <x-slot:title>{{ $template->name }} | {{ $template->type->label() }}</x-slot:title>
            <x-admin.section.card>
                <div class="row">
                    <div class="col-10">
                        {{ $template->description  }}</div>
                    <div class="col-2">
                        <a class="btn btn-primary" href="{{ route('settings.template.form', ['template' => $template,]) }}">
                            {{ Icon::edit() }} Edit Template
                        </a>
                    </div>
                </div>
            </x-admin.section.card>
            <x-admin.section.card>
                {!! $template->content !!}
            </x-admin.section.card>
        </x-admin.section.accordion>
    @endforeach
</div>
