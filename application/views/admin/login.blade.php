@layout('layout.main')

@section('page_title')
Log In
@endsection

@section('page_content')
<div id="admin_login">
	{{ Form::open('admin/login') }}
		<legend>{{ __('vgc.menu_login') }}</legend>

		{{ Form::token() }}
		{{ Form::get_errors($errors) }}

		{{ Form::label('username', __('vgc.login_name_label')) }}
		{{ Form::text('username', Input::old('username')) }}
		<br>

		{{ Form::label('password', __('vgc.login_password_label')) }}
		{{ Form::password('password') }}
		<br>

		<input type="submit" name="admin_login_form_submitted" value="{{ __('vgc.login_submit') }}" class="btn btn-primary">
		<input type="submit" name="admin_login_form_lostpassword" value="{{ __('vgc.login_lost_password') }}">
	</form>
</div> 
<!-- /#admin_login -->
@endsection