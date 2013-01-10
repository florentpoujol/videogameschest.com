<?php
if (is_admin()) $devs = Dev::all('id', 'name');
else $devs = user()->devs;
?>
<div id="selecteditdeveloper">
    {{ Former::open_vertical(route('post_selecteditdeveloper'))->rules(array('dev_name' => 'required')) }} 
        {{ Form::token() }}

        {{ Former::text('dev_name', lang('developer.edit.select_profile_help'))->useDatalist($devs, 'name')->placeholder(lang('developer.edit.select_profile_placeholder')) }}
        {{-- Former::select('dev_id', 'Name')->fromQuery($devs) --}}

        {{ Former::primary_submit(lang('developer.edit.submit')) }}
    </form>
</div> <!-- /#selecteditdeveloper-form --> 
