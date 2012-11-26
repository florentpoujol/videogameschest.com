<div id="admin_login">
	{{ Form::open('admin/login') }}
		<legend>{{ __('vgc.menu_login') }}</legend>

		{{ Form::token() }}
		{{ HTML::get_messages($errors) }}

		{{ Former::text('username')->value(Input::old('username'))->label(__('vgc.login_name_label')) }}
		<br>

		{{ Form::label('password', __('vgc.login_password_label')) }}
		{{ Form::password('password') }}
		<br>

		<input type="submit" name="login_form_submitted" value="{{ __('vgc.login_submit') }}" class="btn btn-primary"> <br>
		<hr>

		<p>If you lost your password, just fill the username field and click the button below to get a new temporary password by email.</p>
		<input type="submit" name="lostpassword" value="{{ __('vgc.login_lost_password') }}">
	</form>
</div> 
<!-- /#admin_login -->
