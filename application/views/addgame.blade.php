<?php
$rules = array(
	'name' => 'required|min:5|unique:games',
	'developer_id' => 'required|exists:developers',
	'logo' => 'url|active_url',
	'website' => 'url|active_url',
	'blogfeed' => 'url|active_url',
	'soundtrackurl' => 'url|active_url',
	'publisherurl' => 'url|active_url',
);

$old = Input::old();
if ( ! empty($old)) {
	Former::populate($old);
}

if (IS_ADMIN) {
	$devs = Dev::get(array('id', 'name'));
} else {
	$devs = Dev::where('privacy', '=', 'public')->get(array('id', 'name'));
}
var_dump($devs);
?>

<div id="addgame_form">
	{{ Former::open_vertical('admin/adddeveloper')->rules($rules) }} 
		<legend>{{ lang('game.add.title') }}</legend>
		{{ Form::token() }}
		
		{{ Former::text('name', lang('game.fields.name')) }}

		{{ Former::select('developer_id', lang('game.fields.developer'))->options($devs) }}
		
		{{ Former::textarea('pitch', lang('game.fields.pitch')) }}

		{{ Former::url('logo', lang('game.fields.logo')) }}
		{{ Former::url('website', lang('game.fields.website')) }}
		{{ Former::url('blogfeed', lang('game.fields.blogfeed')) }}
		{{ Former::url('soundtrackurl', lang('game.fields.soundtrackurl')) }}

		{{ Former::text('publishername', lang('game.fields.publishername')) }}
		{{ Former::url('publisherurl', lang('game.fields.publisherurl')) }}

		{{ Former::number('price', lang('game.fields.price')) }}

		
		<?php
		foreach (Game::$array_items as $item):
			$items = Config::get('vgc.'.$item);
			$options = get_array_lang($items, $item.'.');
			$size = count($items);
		    if ($size > 10 ) {
		        $size = 10;
		    }
		?>
			{{ Former::multiselect($item.'[]', lang('game.fields.'.$item))->options($options)->size($size)->help(lang('game.fields.'.$item.'_help')) }}
		@endforeach
		
		<?php
		$nu_select = array('socialnetworks', 'stores'); // name url select
		foreach ($nu_select as $item):
		?>
			<fieldset>
				<legend>{{ lang('game.fields.'.$item.'_title') }}</legend>

				@for ($i = 1; $i < 5; $i++)		
					{{ Former::select($item.'[names][]', lang('game.fields.'.$item.'_name'))->options(get_array_lang(Config::get('vgc.'.$item), $item.'.')) }} 
					{{ Former::url($item.'[urls][]', lang('game.fields.'.$item.'_url')) }}
				@endfor
			</fieldset>
		@endforeach

		<?php
		$nu_text = array('screenshots', 'videos');
		foreach ($nu_text as $item):
		?>
			<fieldset>
				<legend>{{ lang('game.fields.'.$item.'_title') }}</legend>

				@for ($i = 1; $i < 5; $i++)		
					{{ Former::text($item.'[names][]', lang('game.fields.'.$item.'_name')) }} 
					{{ Former::url($item.'[urls][]', lang('game.fields.'.$item.'_url')) }}
				@endfor
			</fieldset>
		@endforeach

		<input type="submit" value="{{ lang('game.add.submit') }}" class="btn btn-primary">
	</form>
</div>
<!-- /#user_form --> 

