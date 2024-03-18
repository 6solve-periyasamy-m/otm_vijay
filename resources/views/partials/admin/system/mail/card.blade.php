@php /** @var \App\Mail\Storage\TemplatedMail $template */ @endphp
<x-admin.section.card>
    <div class="d-flex justify-content-between">
        <div>
            <h4 class="fw-bold">
                {{ Icon::minimize() }} {{ $template->getName() }}
            </h4>
        </div>
        <div>
            <a href="{{ $template->getEditUrl() }}"
               class="btn btn-outline-success btn-sm mb-1">
                {{ Icon::edit() }}Edit
            </a>
            <a href="{{ $template->getDemoUrl() }}" class="btn btn-outline-info btn-sm mb-1">
                {{ Icon::email() }}Demo
            </a>
        </div>
    </div>
</x-admin.section.card>
<div class="collapse" id="mail-template-{{ $template->getCode()}}">
    <x-admin.section.card>
        <x-slot:title>
            {{ $template->getSubject() }}
        </x-slot:title>
        {!! $template->getBody() !!}
    </x-admin.section.card>
</div>
