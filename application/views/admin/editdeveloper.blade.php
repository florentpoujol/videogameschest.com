<?php
$rules = array(
	'name' => 'required|min:5',
    'email' => 'required|min:5|email',
);


$profile = Profile::find($profile_id);
Former::populate($profile->data_array);

$old = Input::old();

if ( ! empty($old)) {
	Former::populate($old);
}

?>

<div id="editdeveloper_form">
	{{ Former::open_vertical('admin/adddeveloper')->rules($rules) }} 
		<legend>Edit your developer profile</legend>
		{{ Form::token() }}

		{{ Former::text('name', __('vgc.developer_name'))->value($profile->name) }}

		{{ Former::textarea('pitch', __('vgc.developer_pitch')) }}

		{{ Former::url('logo', __('vgc.developer_logo')) }}
		{{ Former::url('website', __('vgc.developer_website')) }}
		{{ Former::url('blogfeed', __('vgc.developer_blogfeed')) }}

		{{ Former::number('teamsize', __('vgc.developer_teamsize')) }}

		{{ Former::select('country')->options(get_array_lang(Config::get('vgc.countries'), 'countries_')) }}


		

		<input type="submit" value="{{ __('vgc.adddeveloper_submit') }}" class="btn btn-primary">
	</form>
</div>
<!-- /#user_form --> 
