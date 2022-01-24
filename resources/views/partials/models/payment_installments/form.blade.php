@include('partials.fields.date', ['name' => 'Due On', 'field' => 'due_on', 'value' => $due_on ?? null])
@include('partials.fields.checkbox', ['name' => 'Is this a Percentage?', 'field' => 'is_percentage', 'value' => isset($paymentInstallment) ? $paymentInstallment->is_percentage : true,])
@include('partials.fields.text', ['name' => 'Amount', 'field' => 'amount', 'value' => $amount ?? null])
@include('partials.fields.submit')
