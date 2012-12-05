<?php
$rules = array(
	'name' => 'required|min:5|unique:users,username',
	'email' => 'required|min:5|unique:users|email',
	'logo' => 'url|active_url',
	'website' => 'url|active_url',
	'blogfeed' => 'url|active_url',
	'teamsize' => 'min:1'
);

$old = Input::old();
if ( ! empty($old)) {
	Former::populate($old);
}

?>

<div id="adddeveloper_form">
	{{ Former::open_vertical('admin/adddeveloper')->rules($rules) }} 
		<legend>{{ __('vgc.adddeveloper_title') }}</legend>
		{{ Form::token() }}
		
		{{ Former::text('name', __('vgc.developer_name')) }}
		{{ Former::email('email', __('vgc.developer_email')) }}

		{{ Former::textarea('pitch', __('vgc.developer_pitch')) }}

		{{ Former::url('logo', __('vgc.developer_logo')) }}
		{{ Former::url('website', __('vgc.developer_website')) }}
		{{ Former::url('blogfeed', __('vgc.developer_blogfeed')) }}

		{{ Former::number('teamsize', __('vgc.developer_teamsize'))->value(1) }}

		{{ Former::select('country', __('vgc.developer_country'))->options(get_array_lang(Config::get('vgc.countries'), 'countries_')) }}

		<?php
		$multiselect_form_items = array("technologies", "operatingsystems", "devices","stores");
		foreach ($multiselect_form_items as $item):
			$items = Config::get('vgc.'.$item);
			$size = count($items);
		    if ($size > 10 ) {
		        $size = 10;
		    }
		?>
			{{ Former::multiselect($item.'[]', __('vgc.developer_'.$item))->options(get_array_lang($items, $item.'_'))->size($size) }}
		@endforeach
		
		<fieldset>
			<legend>{{ __('vgc.developer_socialnetworks') }}</legend>

			@for ($i = 1; $i < 5; $i++)		
				{{ Former::select('socialnetworks[names][]', __('vgc.developer_socialnetworks_name'))->options(get_array_lang(Config::get('vgc.socialnetworks'), 'socialnetworks_')) }} 
				{{ Former::url('socialnetworks[urls][]', __('vgc.developer_socialnetworks_url')) }}
			@endfor
		</fieldset>

		<input type="submit" value="{{ __('vgc.adddeveloper_submit') }}" class="btn btn-primary">
	</form>
</div>
<!-- /#user_form --> 
