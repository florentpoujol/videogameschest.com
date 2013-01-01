<?php
$rules = array(
    'username' => 'required|min:5',
    'email' => 'required|min:5|email',
    'password' => 'min:5|confirmed',
    'password_confirmation' => 'min:5|required_with:password',
    'oldpassword' => 'min:5|required_with:password',
    'type' => 'required|in:dev,admin'
);

$user = User::find($user_id);

$a_user = $user->to_array();
if ($a_user['crosspromotion_subscription'] == 0) unset($a_user['crosspromotion_subscription']);
Former::populate($a_user);

$old = Input::old();
if ( ! empty($old)) {
    Former::populate($old);
}
?>
<div id="edituser">
    <h2>{{ lang('admin.user.edit_title') }}</h2>

    {{ Former::open_vertical('admin/edituser')->rules($rules) }}    
        {{ Form::token() }}
        
        {{ Former::hidden('id', $user->id) }}
        {{ lang('admin.user.id') }} : {{ $user->id }} <br>
        <br>

        {{ Former::text('username', lang('common.name')) }}

        {{ Former::email('email') }}

        {{ Former::xlarge_text('secret_key', 'Secret key')->help(lang('admin.user.secret_key_help')) }}

        @if (IS_ADMIN)
            {{ Former::text('type', 'Account type')->help('"dev" or "admin"') }}
        @endif

        {{ Former::password('password') }}

        {{ Former::password('password_confirmation', 'Password Confirmation') }}

        {{ Former::password('old_password', 'Old password')->help(lang('admin.user.old_password_help')) }}

        <hr>

        <h3>{{ lang('admin.user.subscription_title') }}</h3>

        @if (IS_ADMIN)
            {{ Former::checkbox('crosspromotion_subscription', '')->text('Cross promotion') }}
        @else
            <p>The cross-promotion service does not yet required a subscription to be active.</p>
        @endif

        <hr>

        <input type="submit" value="Edit this user" class="btn btn-primary">
    </form>
</div> <!-- /#edituser --> 
