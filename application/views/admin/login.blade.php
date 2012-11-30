<div id="admin_login">
	{{ Former::open_vertical()->rules(array('username' => 'required', 'password' => 'required')) }}
		<legend>{{ __('vgc.menu_login') }}</legend>
		
		{{ Form::token() }}
		
		{{ Former::text('username', __('vgc.login_name_label')) }}
		
		{{ Former::password('password', __('vgc.login_password_label')) }}
		
		<input type="submit" name="login_form_submitted" value="{{ __('vgc.login_submit') }}" class="btn btn-primary">
	</form>

	<hr>

	{{ Former::open_inline()->action('admin/lostpassword')->rules(array('username' => 'required')) }}
		{{ Form::token() }}

		<p>
			If you lost your password, just fill the field below with your username, email or user id and click the button to get a new temporary password by email.
		</p>

		{{ Former::text('username', __('vgc.login_name_label'))->placeholder('User name, email or id') }} 

		<input type="submit" name="lostpassword" value="{{ __('vgc.login_lost_password') }}" class="btn btn-info">
	</form>
</div> 
<!-- /#admin_login -->
