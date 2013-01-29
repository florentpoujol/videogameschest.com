<?php
$rules = array(
    'frequency' => 'required|integer|min:1',
    'profile_count' => 'required|integer|min:1',
    'search_id' => 'integer|min:1',
);
?>
{{ Former::open_vertical(route('post_new_promotion_email'))->rules($rules) }}
        
    <div class="row">
        <div class="span4">
            {{ Former::email('email', lang('common.email'))->placeholder(lang('common.email')) }}
        </div>

        <div class="span4">
            {{ Former::number('frequency', lang('discover.form.frequency'))->value(24)->help(lang('discover.form.frequency_help')) }}
        </div>

        <div class="span4">
            {{ Former::number('profile_count', lang('discover.form.profile_count'))->value(10)->help(lang('discover.form.profile_count_help')) }}
        </div>
    </div>
    
    <hr>

    <div class="row">
        <div class="span4">
            {{ Former::text('search_id', lang('discover.form.search_id'))->help(lang('discover.form.search_id_help')) }}
        </div>

        <div class="span4">
            @if (is_logged_in())
                {{ Former::hidden('user_id', user_id()) }}

                {{ Former::checkbox('use_blacklist', '')->checkboxes(lang('discover.form.use_blacklist')) }}
                <p class="muted">
                    {{ lang('discover.form.blacklist_help', array('blacklist_link'=>route('get_edituser'))) }}
                </p>
            @else
                {{ Former::checkbox('use_blacklist', '')->checkboxes(lang('discover.form.use_blacklist'))->help(lang('discover.form.blacklist_guest_help', array('register_link'=>route('get_register'))))->disabled() }}
            @endif
        </div>

        <div class="span4">
            {{ Former::primary_submit(lang('discover.form.feed_submit')) }}
        </div>
    </div>

    <hr>

    
{{ Former::close() }}