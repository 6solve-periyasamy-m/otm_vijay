@include('partials.fields.text', ['name' => 'Name', 'field' => 'name', 'value' => $name ?? null, 'width' => 4,])
@include('partials.fields.text', ['name' => 'Email Address', 'field' => 'email', 'value' => $email ?? null,  'width' => 6,])
@include('partials.fields.file', ['name' => 'Avatar', 'field' => 'avatar', 'width' => 2,])
<hr class="splitter">
@if(isset($user) && Auth::user()->id != $user->id)
    @include('partials.fields.dropdown', ['name' => 'Role', 'field' => 'role', 'values' => $roles, 'selected' => $current,])
@elseif(isset($user) && Auth::user()->id == $user->id)
    @include('partials.fields.password', ['name' => 'Current Password', 'field' => 'current_password', 'width' => 4,])
    @include('partials.fields.password', ['name' => 'New Password', 'field' => 'new_password', 'width' => 4,])
    @include('partials.fields.password', ['name' => 'Confirm Password', 'field' => 'new_password_confirmation', 'width' => 4,])
@else
    @include('partials.fields.dropdown', ['name' => 'Role', 'field' => 'role', 'values' => $roles, 'selected' => $current, 'width' => 6,])
    @include('partials.fields.password', ['name' => 'Password', 'field' => 'password', 'width' => 6,])
@endif
<hr class="splitter">
@include('partials.fields.submit')
