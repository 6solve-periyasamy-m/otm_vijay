@include('partials.fields.text', ['name' => 'Amount', 'field' => 'amount', 'value' => $amount ?? null,])
@include('partials.fields.text', ['name' => 'Reason', 'field' => 'reason', 'value' => $reason ?? null,])
@include('partials.fields.date', ['name' => 'Date', 'field' => 'date', 'value' => $date ?? null,])
@include('partials.fields.submit')
