<div id="admin_login">
	{{ Former::open_vertical()->rules(array('username' => 'required', 'password' => 'required')) }}
		<h2>{{ lang('menu.login.submit') }}</h2>
		
		{{ Form::token() }}
		
		{{ Former::text('username', '')->placeholder(lang('menu.login.name_label')) }}
		
		{{ Former::password('password', '')->placeholder(lang('menu.login.password_label')) }}

		{{ Former::checkbox('keep_logged_in', '')->text(lang('menu.login.keep_logged_in_label'))->check() }}
		
		<input type="submit" name="login_form_submitted" value="{{ lang('menu.login.submit') }}" class="btn btn-primary">
	</form>

	<hr>

	{{ Former::open_inline('admin/lostpassword')->rules(array('username' => 'required')) }}
		{{ Form::token() }}

		{{ Former::text('username', lang('menu.login.name_label'))->placeholder(lang('menu.login.name_label')) }} 
		<input type="submit" name="lostpassword" value="{{ lang('menu.login.lost_password') }}" class="btn btn-info">
	</form>
</div> 
<!-- /#admin_login -->

