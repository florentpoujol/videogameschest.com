@section('page_title')
	{{ lang('profile.profile_select') }}
@endsection
<?php
$profiles = Profile::all(array('id', 'name'));
?>
<div id="select-pofile-to-edit">
    {{ Former::open_vertical(route('post_profile_select'))->rules(array('name' => 'required')) }} 
        {{ Form::token() }}

        {{ Former::text('name', lang('profile.profile_select'))->useDatalist($profiles, 'name')->placeholder(lang('profile.profile_select_placeholder')) }}

        {{ Former::primary_submit(lang('common.submit')) }}
    </form>
</div> <!-- /#select-pofile-to-edit --> 
