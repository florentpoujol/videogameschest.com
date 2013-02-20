<?php
// var $profile_type should exists
if (is_admin()) $profiles = $profile_type::all('id', 'name');
else $profiles = user()->${$profile_type.'s'};
?>
<div id="select-pofile-to-edit">
    {{ Former::open_vertical(route('post_profile_select', $profile_type))->rules(array('name' => 'required')) }} 
        {{ Form::token() }}

        {{ Former::hidden('profile_type', $profile_type) }}

        {{ Former::text('name', lang('profile.profile_select', array('type'=>$profile_type)))->useDatalist($profiles, 'name')->placeholder(lang('profile.profile_select_placeholder')) }}

        {{ Former::primary_submit(lang('common.submit')) }}
    </form>
</div> <!-- /#select-pofile-to-edit --> 
