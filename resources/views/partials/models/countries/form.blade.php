@include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $name ?? null,])
@include('partials.fields.text', ['name' => 'Code', 'field' => 'code', 'value' => $code ?? null,])
@include('partials.fields.text', ['name' => 'Currency', 'field' => 'currency', 'value' => $currency ?? null,])
@include('partials.fields.submit')
