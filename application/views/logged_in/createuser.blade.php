<?php
$rules = array(
	'username' => 'required|min:5',
    'email' => 'required|min:5|email',
    'password' => 'required|min:5|confirmed',
    'password_confirmation' => 'required|min:5',
    'type' => 'required|in:dev,admin'
);
?>

<div id="user_form">
	{{ Former::open_vertical(route('post_user_create'))->rules($rules) }} 
		<legend>Add a user account</legend>
		{{ Form::token() }}

		{{ Former::text('type', 'Account type')->help('"dev" or "admin"')->value('dev') }}

		{{ Former::text('username', 'User name') }}

		{{ Former::email('email') }}

		{{ Former::password('password') }}

		{{ Former::password('password_confirmation', 'Password Confirmation') }}

		<input type="submit" value="Add this user" class="btn btn-primary">
	</form>
</div>
<!-- /#user_form --> 
