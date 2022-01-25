@include('partials.fields.date', ['name' => 'Due On', 'field' => 'due_on', 'value' => $due_on ?? null])
@include('partials.fields.text', ['name' => 'Amount', 'field' => 'amount', 'value' => $amount ?? null])
@include('partials.fields.submit')
