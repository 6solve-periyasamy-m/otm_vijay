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
        @include('partials.users.permissions.form.values', ['class' => $field, 'action' => 'delete', 'value' => $data['delete']])
    </td>
</tr>
