@include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $name ?? null,])
@include('partials.fields.text', ['name' => 'Description', 'field' => 'description', 'value' => $name ?? null,])
@include('partials.fields.date', ['name' => 'Audit Date', 'field' => 'audit_date', 'value' => $audit_date ?? null,])
@include('partials.models.addresses.switcher', [
                    'location_type_id' => isset($address) ? $address->location_type_id : 0,
                    'address_line_1' => isset($address) ? $address->address_line_1 : "",
                    'address_line_2' => isset($address) ? $address->address_line_2 : "",
                    'town' => isset($address) ? $address->town : "",
                    'region' => isset($address) ? $address->region : "",
                    'country_id' => isset($address) ? $address->country_id : 0,
                    'postcode' => isset($address) ? $address->postcode : "",
                ])
@include('partials.fields.text', ['name' => 'Currency', 'field' => 'currency', 'value' => $currency ?? null,])
@include('partials.fields.submit')
