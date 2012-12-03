<?php
$devs = Dev::get(array('id', 'name'));
?>
<div id="selecteditdeveloper_form">
	{{ Former::open_vertical('admin/selecteditdeveloper')->rules(array('dev_name' => 'required')) }} 
		<legend>Select the developer to edit</legend>
		{{ Form::token() }}

		{{ Former::text('dev_name', 'Name or id')->useDatalist($devs, 'name') }}

		<input type="submit" value="Edit this dev" class="btn btn-primary">
	</form>
</div>
<!-- /#user_form --> 
