<tr>
    <th scope="row"><h6 style="text-decoration: underline">{{ $data['name'] }}</h6></th>
    <td>
        @include('partials.users.permissions.form.values', ['class' => $field, 'action' => 'read', 'value' => $data['read']])
    </td>
    <td>
        @include('partials.users.permissions.form.values', ['class' => $field, 'action' => 'create', 'value' => $data['create']])
    </td>
    <td>
        @include('partials.users.permissions.form.values', ['class' => $field, 'action' => 'update', 'value' => $data['update']])
    </td>
    <td>
        @include('partials.users.permissions.form.values', ['class' => $field, 'action' => 'self-update', 'value' => $data['self-update'] ?? false])
    </td>
    <td>
        @include('partials.users.permissions.form.values', ['class' => $field, 'action' => 'delete', 'value' => $data['delete']])
    </td>
    <td>
        @include('partials.users.permissions.form.values', ['class' => $field, 'action' => 'self-delete', 'value' => $data['self-delete'] ?? false])
    </td>
    <td>
        @if(array_key_exists('costing', $data))
            @include('partials.users.permissions.form.values', ['class' => $field, 'action' => 'costing', 'value' => $data['costing']])
        @endif
    </td>
    <td>
        <div class="form-group">
            <input type="checkbox" name="{{ $class }}-all" class="{{ $group }} form-check-input" id="{{ $class }}-all"
                   @if((old($class.'-all') != null && old($class.'-all') == 'on')||(isset($value) && $value == true)) checked @endif
                   onchange="multiChanger('{{$field}}-all', '{{$field}}')">
        </div>
    </td>
</tr>
