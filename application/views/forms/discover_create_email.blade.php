<?php
$rules = array(
    'email' => 'required|email',
    'frequency' => 'required|integer|min:12|max:744',
    'profile_count' => 'required|integer|min:1|max:500',
    'search_id' => 'required|integer|min:1',
);

if (is_logged_in()) $rules['email'] = 'email';
// disabled email field prevent the field to be sent
?>
{{ Former::open_vertical(route('post_discover_newsletter_create'))->rules($rules) }}
    {{ Form::token() }}
        
    <div class="row">
        <div class="span4">
            @if (is_logged_in())
                {{ Former::email('email', lang('common.email'))->placeholder(lang('common.email'))->value(user()->email) }}
            @else
                {{ Former::email('email', lang('common.email'))->placeholder(lang('common.email')) }}
            @endif
        </div>

        <div class="span4">
            {{ Former::number('frequency', lang('discover.form.frequency'))->value(168)->help(lang('discover.form.frequency_help')) }}
        </div>

        <div class="span4">
            {{ Former::number('profile_count', lang('discover.form.profile_count'))->value(20)->help(lang('discover.form.profile_count_help')) }}
        </div>
    </div>
    
    <hr>

    <div class="row">
        <div class="span4">
            @if (isset($search_id))
                {{ Former::text('search_id', lang('vgc.discover.form.search_id'))->help(lang('vgc.discover.form.search_id_help'))->value($search_id) }}
            @else
                {{ Former::text('search_id', lang('vgc.discover.form.search_id'))->help(lang('vgc.common.search_id_redirect', array('search_page_url' => route('get_search_page'))).' <br> '.lang('vgc.discover.form.search_id_help')) }}
            @endif
        </div>

        <div class="span4">
            @if (is_logged_in())
                {{ Former::checkbox('use_blacklist', '')->text(lang('discover.form.use_blacklist'))->help(lang('discover.form.blacklist_help', array('blacklist_link'=>route('get_user_update'))))->id('email_blacklist') }}
            @else
                {{ Former::checkbox('use_blacklist', '')->text(lang('discover.form.use_blacklist'))->help(lang('discover.form.blacklist_guest_help', array('register_link'=>route('get_register_page'))))->disabled() }}
            @endif
        </div>

        <div class="span4">
            {{ Former::primary_submit(lang('discover.form.email.submit')) }}
        </div>
    </div>

{{ Former::close() }}
