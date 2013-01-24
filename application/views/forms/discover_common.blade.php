



{{ Former::text('search_id', lang('discover.form.search_id'))->help(lang('discover.form.search_id_help')) }}

@if (is_logged_in())
    {{ Former::hidden('user_id', user_id()) }}
@endif

{{ Former::checkbox('use_blacklist', '')->checkboxes(lang('discover.form.use_blacklist')) }}