@include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $name ?? null,])
@include('partials.fields.text', ['name' => 'Email Address', 'field' => 'email', 'value' => $email ?? null, ])
@include('partials.fields.password', ['name' => 'Password', 'field' => 'password', ])
@include('partials.fields.password', ['name' => 'Confirm Password', 'field' => 'password_confirmed', ])
@include('partials.fields.file', ['name' => 'Avatar', 'field' => 'avatar',])
@if(!isset($user) || Auth::user()->id != $user->id)
    @include('partials.fields.dropdown', ['name' => 'Role', 'field' => 'role', 'values' => $roles, 'selected' => $current,])
@endif
@include('partials.fields.submit')
