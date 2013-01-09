<?php
if (is_admin()) $devs = Dev::all('id', 'name');
else $devs = user()->devs;
?>
<div id="selecteditdeveloper-form">
	{{ Former::open_vertical(route('post_selecteditdeveloper'))->rules(array('dev_name' => 'required')) }} 
		<legend>Select the developer to edit</legend>
		{{ Form::token() }}

		{{ Former::text('dev_name', 'Name or id')->useDatalist($devs, 'name') }}
        {{-- Former::select('dev_id', 'Name')->fromQuery($devs) --}}

		<input type="submit" value="Edit this dev" class="btn btn-primary">
	</form>
</div> <!-- /#selecteditdeveloper-form --> 
