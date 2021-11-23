@include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $name ?? null,])
@include('partials.fields.text', ['name' => 'IATA Code', 'field' => 'iata_code', 'value' => $iata_code ?? null,])
@include('partials.fields.prefab.addresses.switcher')
@include('partials.fields.submit')
