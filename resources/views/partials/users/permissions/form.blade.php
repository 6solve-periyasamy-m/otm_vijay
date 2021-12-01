@include('partials.fields.text', ['name' => 'Name', 'field' => 'title', 'value' => $title ?? null, 'width' => 5,])
@include('partials.fields.text', ['name' => 'Level', 'field' => 'level', 'value' => $level ?? null, 'width' => 5,])
<div class="form-group col-12 col-xl-2">
    <label for="submit">Apply Changes</label>
    <button id="submit" type="submit" class="form-control btn btn-primary">Submit</button>
</div>

<div class="mx-auto w-100 border text-center" style="margin-bottom: 4px;">
    <table class="table table-striped">
        <thead class="thead-dark">
        <tr >
            <th scope="col">Section</th>
            <th scope="col">View</th>
            <th scope="col">Create</th>
            <th scope="col">Edit</th>
            <th scope="col">Delete</th>
            <th scope="col">All</th>
        </tr>
        </thead>
        <tbody>
            @foreach($permissions as $group => $classes)
                <tr>
                    <td colspan="5"><h4 style="text-decoration: underline">{{ $group }}</h4></td>
                    <td>
                        <div class="form-group">
                            <input type="checkbox" name="{{ str_replace(' ', '', $group).'Group' }}-all"
                                   class="form-check-input" id="{{ str_replace(' ', '', $group).'Group' }}-all"
                                   onchange="multiChanger('{{ str_replace(' ', '', $group).'Group' }}-all', '{{ str_replace(' ', '', $group).'Group' }}')">
                        </div>
                    </td>
                </tr>
                @foreach($classes as $class => $data)
                    @include('partials.users.permissions.form.row',
                                ['field' => $class, 'data' => $data, 'group' => str_replace(' ', '', $group).'Group'])
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
