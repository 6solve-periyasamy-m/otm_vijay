<div class="col-12 col-xl-8">
    @include('partials.fields.ckeditor', [
        'field' => 'body',
        'name' => 'Email Body',
        'value' => $body
    ])
    @include('partials.fields.submit')
    <a class="d-inline-flex btn btn-amber" href="{{ $demo }}">Demo Email</a>
</div>
<div class="col-12 col-xl-4">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Code</th>
            <th scope="col">Example</th>
        </tr>
        </thead>
        <tbody>
        @foreach($codes as $code => $example)
            <tr>
                <th scope="row">[{{$code}}]</th>
                <td>{{$example}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
