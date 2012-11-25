<?php
// form to edit/add a user form
if ( ! isset($form)) {
	$form = array();
}
elseif (is_object($form)) {
	$form = get_object_vars($form);
}

$form_items = array("type", "id", "username", "email", "password", "password_confirmation", "key", "created_at", "updated_at");

foreach ($form_items as $item) 
{
	if( ! isset($form[$item])) {
		$form[$item] = Input::old($item);
	}
}

if ($form['type'] == '')
	$form['type'] = 'dev';


$form_legend = 'Edit your user account';
if (METHOD == 'adduser') {
	$form_legend = 'Add a user account';
}
?>

<div id="user_form">
	{{ Form::open("admin/".METHOD) }} 
		<legend>{{ $form_legend }}</legend>
		{{ Form::token() }}
		<!-- forms errors and success -->
		{{ HTML::get_messages($errors) }} 
		<!-- /forms errors and success -->

@if (METHOD == 'edituser')
		<p>Your "developer" user account is a different entity than your developer profile, while both are created at the same time, with the same name.</p>

		Account creation date : {{ $form["created_at"] }} <br>
		Account last update : {{ $form["updated_at"] }} <br>
		<br>

		Account/User Id : {{ $form["id"] }} <br>
		{{ Form::hidden('form[id]', $form['id']) }}
		<span class="help-inline">Keep in mind that it's not the same as your developer profile id.</span> <br>
		<br>

		Account key : 
	@if (IS_DEVELOPER) 	
		{{ $form["key"] }} <br>
	@elseif (IS_ADMIN)
		<br>
		<input class="input-xlarge" type="text" name="form[key]" value="{{ $form["key"] }}">
	@endif
		<span class="help-inline">It's a "secret" key used to retreive "secret" data as your message's RSS feed.</span>
		<br>
@endif
	@if (IS_ADMIN)
		<label for="account_type">Account type</label>
		<input type="text" name="form[type]" id="account_type" placeholder="dev or admin" value="{{ $form['type'] }}">
		<span class="help-inline">"dev" or "admin"</span> 
	@endif
		
		<label for="username">User name</label>
		<input type="text" name="form[username]" id="username" placeholder="User name" value="{{ $form['username'] }}">
		<br>

		<label for="" for="email">Email</label>
		<input type="email" name="form[email]" id="email" placeholder="Email" value="{{ $form['email'] }}">
		<br>
		
		<label for="password">Password</label>
		<input type="password" name="form[password]" id="password" placeholder="Write here only to update">
		<br>

		<label for="password2">Password confirmation</label>
		<input type="password" name="form[password_confirmation]" id="password_confirmation" placeholder="Same as above" >
		<br>

	@if (METHOD == 'edituser')
		<label for="oldpassword">Old password</label>
		<input type="password" name="form[oldpassword]" id="oldpassword" placeholder="Your old password" >
		<span class="help-inine">In order to update your password, enter your old password here.</span> <br>
		<br>
	@endif

		<input type="submit" name="user_form_submitted" value="Submit">
	</form>
</div>
<!-- /#user_form --> 
