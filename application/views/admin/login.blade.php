@section('page_title')
	{{ lang('login.title') }}
@endsection

<div id="login-form">
	<h2>{{ lang('login.title') }}</h2>

	<hr>

	{{ Former::open_vertical(route('post_login'))->rules(array('username' => 'required', 'password' => 'required')) }}
		{{ Form::token() }}
		
		{{ Former::text('username', '')->placeholder(lang('login.name_label')) }}
		
		{{ Former::password('password', '')->placeholder(lang('login.password_label')) }}

		{{ Former::checkbox('keep_logged_in', '')->text(lang('login.keep_logged_in_label'))->check() }}
		
		{{ Former::primary_submit(lang('login.submit')) }}
	</form>

	<hr>

	{{ Former::open_inline(route('post_lostpassword'))->rules(array('username' => 'required')) }}
		{{ Form::token() }}

		{{ Former::text('username', '')->placeholder(lang('login.name_label')) }} 
		{{ Former::info_submit(lang('login.lost_password')) }}
	</form>
</div> 
<!-- /#admin_login -->

