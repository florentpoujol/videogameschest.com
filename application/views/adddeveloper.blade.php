<?php
$rules = array(
	'name' => 'required|min:5',
    'email' => 'required|min:5|email',
);
?>

<div id="adddeveloper_form">
	{{ Former::open_vertical('admin/adddeveloper')->rules($rules) }} 
		<legend>{{ __('vgc.adddeveloper_legend') }}</legend>
		{{ Form::token() }}

		{{ Former::text('name', __('vgc.developer_name')) }}

		{{ Former::email('email', __('vgc.developer_email')) }}

		{{ Former::textarea('pitch', __('vgc.developer_pitch')) }}
		

		<input type="submit" value="{{ __('vgc.adddeveloper_submit') }}" class="btn btn-primary">
	</form>
</div>
<!-- /#user_form --> 
