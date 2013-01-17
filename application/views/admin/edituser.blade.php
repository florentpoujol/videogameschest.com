@section('page_title')
    {{ lang('user.edit_title') }}
@endsection

<?php
$rules = array(
    'username' => 'required|min:5',
    'email' => 'required|min:5|email',
    'url_key' => 'min:10|alpha_num',
    'password' => 'min:5|confirmed',
    'password_confirmation' => 'min:5|required_with:password',
    'oldpassword' => 'min:5|required_with:password',
);

$user = User::find($user_id);

Former::populate($user);

$old = Input::old();
if ( ! empty($old)) Former::populate($old);
?>
<div id="edituser">
    <h1>{{ lang('user.edit_title') }}</h1>

    <hr>

    {{ Former::open_vertical(route('post_edituser'))->rules($rules) }}    
        {{ Form::token() }}

        @if (is_admin())
            {{ Former::hidden('id', $user->id) }}
        @endif

        {{ lang('user.id') }} : {{ $user->id }} <br>
        
        <br>

        {{ Former::text('username', lang('common.name')) }}

        {{ Former::email('email', lang('common.email')) }}

        {{-- Former::text('url_key', 'Url key')->help(lang('user.url_key_help')) --}}

        @if (is_admin())
            {{ Former::text('type', 'Account type')->help('"user," "dev" or "admin"') }}
        @endif

        {{ Former::password('password') }}

        {{ Former::password('password_confirmation', 'Password Confirmation') }}

        {{ Former::password('old_password', 'Old password')->help(lang('user.old_password_help')) }}

        <hr>

        {{ Former::primary_submit(lang('user.edit_title')) }}     
    </form>
</div> <!-- /#edituser --> 
