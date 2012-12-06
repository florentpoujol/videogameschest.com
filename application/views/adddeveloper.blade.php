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
		<legend>{{ lang('adddeveloper_title') }}</legend>
		{{ Form::token() }}
		
		{{ Former::text('name', lang('developer_name')) }}
		{{ Former::email('email', lang('developer_email')) }}

		{{ Former::textarea('pitch', lang('developer_pitch')) }}

		{{ Former::url('logo', lang('developer_logo')) }}
		{{ Former::url('website', lang('developer_website')) }}
		{{ Former::url('blogfeed', lang('developer_blogfeed')) }}

		{{ Former::number('teamsize', lang('developer_teamsize'))->value(1) }}

		{{ Former::select('country', lang('developer_country'))->options(get_array_lang(Config::get('vgc.countries'), 'countries.')) }}

		<?php
		$multiselect_form_items = array("technologies", "operatingsystems", "devices","stores");
		foreach ($multiselect_form_items as $item):
			$items = Config::get('vgc.'.$item);
			$size = count($items);
		    if ($size > 10 ) {
		        $size = 10;
		    }
		?>
			{{ Former::multiselect($item.'[]', lang('developer_'.$item))->options(get_array_lang($items, $item.'.'))->size($size) }}
		@endforeach
		
		<fieldset>
			<legend>{{ lang('developer_socialnetworks') }}</legend>

			@for ($i = 1; $i < 5; $i++)		
				{{ Former::select('socialnetworks[names][]', lang('developer_socialnetworks_name'))->options(get_array_lang(Config::get('vgc.socialnetworks'), 'socialnetworks.')) }} 
				{{ Former::url('socialnetworks[urls][]', lang('developer_socialnetworks_url')) }}
			@endfor
		</fieldset>

		<input type="submit" value="{{ lang('adddeveloper_submit') }}" class="btn btn-primary">
	</form>
</div>
<!-- /#user_form --> 

