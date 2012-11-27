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
Former::populate($user);

$old = Input::old();

if ( ! empty($old)) {
	Former::populate($old);
}
?>

<div id="user_form">
	{{ Former::open_vertical()->rules($rules) }} 
		<legend>Edit a user account</legend>
		{{ Form::token() }}

		<p>Your "developer" user account is a different entity than your developer profile, while both are created at the same time, with the same name.</p>

		{{ Former::datetime('created_at', 'Account creation date') }}
		
		{{ Former::datetime('updated_at', 'Account last update') }}

		{{ Former::number('id', 'Account/User Id')->help("Keep in mind that it's not the same as your developer profile id.") }}

		{{ Former::xlarge_text('secret_key')->help("It's a \"secret\" key used to retreive \"secret\" data as your message's RSS feed.") }}

		@if (IS_ADMIN)
		{{ Former::text('type', 'Account type')->help('"dev" or "admin"') }}
		@endif

		{{ Former::text('username', 'User name') }}

		{{ Former::email('email') }}

		{{ Former::password('password') }}

		{{ Former::password('password_confirmation', 'Password Confirmation') }}

		{{ Former::password('old_password')->help('In order to update your password, enter your old password here.') }}

		<input type="submit" value="Edit this user" class="btn btn-primary">
	</form>
</div>
<!-- /#user_form --> 
