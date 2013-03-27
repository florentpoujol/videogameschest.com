@section('page_title')
	{{ lang('vgc.profile.profile_select', array('profile_type'=>$profile_type)) }}
@endsection
<?php
// var $profile_type should exists
if (is_admin()) $profiles = $profile_type::all('id', 'name');
else $profiles = user()->${$profile_type.'s'};
?>
<div id="select-pofile-to-edit">
    {{ Former::open_vertical(route('post_profile_select', $profile_type))->rules(array('name' => 'required')) }} 
        {{ Form::token() }}

        {{ Former::hidden('profile_type', $profile_type) }}

        {{ Former::text('name', lang('vgc.profile.profile_select', array('profile_type'=>$profile_type)))->useDatalist($profiles, 'name')->placeholder(lang('vgc.profile.profile_select_placeholder')) }}

        {{ Former::primary_submit(lang('vgc.common.submit')) }}
    </form>
</div> <!-- /#select-pofile-to-edit --> 
