<?php
$rules = array(
    // 'type' => 'required|in:rss,atom',
    'frequency' => 'required|integer|min:12|max:744',
    'profile_count' => 'required|integer|min:1|max:500',
    'search_id' => 'required|integer|min:1',
);

if (is_logged_in()) {
    $feed = user()->promotionFeed;

    if ( ! is_null($feed)) {
        Former::populate($feed);
    }
}
?>
{{ Former::open_vertical(route('post_discover_feed_create'))->rules($rules) }}
    {{ Form::token() }}

    <div class="row">
        <div class="span4">
            {{ Former::number('frequency', lang('vgc.discover.form.frequency'))->value(24)->help(lang('vgc.discover.form.frequency_help')) }}
        </div>

        <div class="span4">
            {{ Former::number('profile_count', lang('vgc.discover.form.profile_count'))->value(10)->help(lang('vgc.discover.form.profile_count_help')) }}
        </div>

        <div class="span4">
            @if (isset($search_id))
                {{ Former::text('search_id', lang('vgc.discover.form.search_id'))->help(lang('vgc.discover.form.search_id_help'))->value($search_id) }}
            @else
                {{ Former::text('search_id', lang('vgc.discover.form.search_id'))->help(lang('vgc.common.search_id_redirect', array('search_page_url' => route('get_search_page'))).' <br> '.lang('vgc.discover.form.search_id_help')) }}
            @endif
        </div>
    </div>
    
    <hr>

    <div class="row">
        <div class="span4">
            @if (is_logged_in())
                {{ Former::checkbox('use_blacklist', '')->text(lang('vgc.discover.form.use_blacklist'))->help(lang('vgc.discover.form.blacklist_help', array('blacklist_link'=>route('get_user_update'))))->id('feed_blacklist') }}
                </p>
            @else
                {{ Former::checkbox('use_blacklist', '')->text(lang('vgc.discover.form.use_blacklist'))->help(lang('vgc.discover.form.blacklist_guest_help', array('register_link'=>route('get_register_page'))))->disabled() }}
            @endif
        </div>

        <div class="span4">
            @if (isset($feed))
                {{ Former::primary_submit(lang('common.update')) }}
            @else
                {{ Former::primary_submit(lang('vgc.discover.form.feed.submit')) }}
            @endif
        </div>
    </div>
    
{{ Former::close() }}
