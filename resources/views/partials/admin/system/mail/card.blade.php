@php /** @var \App\Mail\Storage\TemplatedMail $template */ @endphp
<div class="card">
    <div class="card-body" data-target="#mail-template-{{ $template->getCode()}}" onclick="toggleAccordion(this)">
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
    </div>
</div>
<div class="collapse" id="mail-template-{{ $template->getCode()}}">
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <h4 class="fw-bold">{{ $template->getSubject() }}</h4>
            </div>
            {!! $template->getBody() !!}
        </div>
    </div>
</div>
