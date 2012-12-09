<div id="admin_login">
	{{ Former::open_vertical()->rules(array('username' => 'required', 'password' => 'required')) }}
		<legend>{{ lang('menu.login.submit') }}</legend>
		
		{{ Form::token() }}
		
		{{ Former::text('username', lang('menu.login.name_label')) }}
		
		{{ Former::password('password', lang('menu.login.password_label')) }}
		
		<input type="submit" name="login_form_submitted" value="{{ lang('menu.login.submit') }}" class="btn btn-primary">
	</form>

	<hr>

	{{ Former::open_inline()->action('admin/lostpassword')->rules(array('username' => 'required')) }}
		{{ Form::token() }}

		<p>{{ lang('menu.login.lostpassword_help') }}</p>

		{{ Former::text('username', lang('menu.login.name_label'))->placeholder(lang('menu.login.name_label')) }} 
		<input type="submit" name="lostpassword" value="{{ lang('menu.login.lost_password') }}" class="btn btn-info">
	</form>
</div> 
<!-- /#admin_login -->
