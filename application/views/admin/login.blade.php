<div id="admin_login">
	{{ Former::open_vertical()->rules(array('username' => 'required')) }}
		<legend>{{ __('vgc.menu_login') }}</legend>
		
		{{ Form::token() }}
		
		{{ Former::text('username', __('vgc.login_name_label')) }}
		
		{{ Former::password('password', __('vgc.login_password_label')) }}
		
		<input type="submit" name="login_form_submitted" value="{{ __('vgc.login_submit') }}" class="btn btn-primary">
		
		<hr>

		<p>If you lost your password, just fill the username field and click the button below to get a new temporary password by email.</p>
		<input type="submit" name="lostpassword" value="{{ __('vgc.login_lost_password') }}" class="btn btn-info">
	</form>
</div> 
<!-- /#admin_login -->
