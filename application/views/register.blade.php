<?php
$rules = array(
	'username' => 'required|min:5',
	'email' => 'required|min:5|email',
	'password' => 'required|min:5|confirmed',
	'password_confirmation' => 'min:5|required|required_with:password',
);
?>
<div id="register-form">
	<h2>{{ lang('register.title') }}</h2>

	<hr>

	{{ Former::open_vertical(route('post_register'))->rules($rules) }}
		{{ Form::token() }}
		
		{{ Former::text('username', '')->placeholder(lang('register.username')) }}

		{{ Former::text('email', '')->placeholder(lang('common.email')) }}
		
		{{ Former::password('password', '')->placeholder(lang('register.password')) }}

		{{ Former::password('password_confirmation', '')->placeholder(lang('register.password_confirmation')) }}
		
		{{ Former::primary_submit(lang('register.submit')) }}
	</form>
</div> <!-- /#register-form -->

