@php
    $authorization = setting('authorization.reminders', 0);
    $authDate = $authorization >= 0 ? Carbon\Carbon::createFromTimestamp($authorization) : null;
    $authorized = \Settings::authorized('authorization.reminders')
@endphp
<x-admin.section.card>
    <div class="row">
        <div class="form-group col-12 col-xl-2">
            <a class="form-control mx-auto btn btn-block btn-primary text-white" href="{{ route('orders.reminders.authorize', ['days' => 3,]) }}">Authorize for 3 Days</a>
        </div>
        <div class="form-group col-12 col-xl-2">
            <a class="form-control mx-auto btn btn-block btn-primary text-white" href="{{ route('orders.reminders.authorize', ['days' => 7,]) }}">Authorize for 7 Days</a>
        </div>
        <div class="form-group col-12 col-xl-2">
            <a class="form-control mx-auto btn btn-block btn-primary text-white" href="{{ route('orders.reminders.authorize', ['days' => 30,]) }}">Authorize for 30 Days</a>
        </div>
        <div class="form-group col-12 col-xl-2">
            <a class="form-control mx-auto btn btn-block btn-primary text-white" href="{{ route('orders.reminders.authorize', ['days' => -1,]) }}">Authorize Forever</a>
        </div>
        <div class="form-group col-12 col-xl-2">
            <a class="form-control mx-auto btn btn-block btn-primary text-white" href="{{ route('orders.reminders.authorize', ['days' => 0,]) }}">Revoke Authorization</a>
        </div>
        <div class="form-group col-12 col-xl-2">
            <a disabled class="form-control mx-auto btn-block btn btn-outline-{{ $authorized ? 'success' : 'danger' }}" style="padding-top: 6px;">
                @if ($authorization < 0)
                    Authorized Forever
                @elseif(!$authorized)
                    Unauthorized
                @else
                    Authorized until {{ f_date($authDate) }}
                @endif
            </a>
        </div>

    </div>
</x-admin.section.card>
