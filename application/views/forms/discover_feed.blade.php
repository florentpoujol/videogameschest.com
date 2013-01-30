<?php
$rules = array(
    'type' => 'required|in:rss,atom',
    'frequency' => 'required|integer|min:12|max:744',
    'profile_count' => 'required|integer|min:1|max:500',
    'search_id' => 'integer|min:1',
);

if (is_logged_in()) {
    $feed = user()->promotionFeed;

    if ( ! is_null($feed)) {
        Former::populate($feed);
    }
}
?>
{{ Former::open_vertical(route('post_create_promotion_feed'))->rules($rules) }}
    {{ Form::token() }}

    <div class="row">
        <div class="span4">
            {{ Former::select('type', lang('discover.form.feed.type'))->options(array('rss'=>'RSS', 'atom'=>'Atom')) }}
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
                {{ Former::checkbox('use_blacklist', '')->text(lang('discover.form.use_blacklist'))->help(lang('discover.form.blacklist_help', array('blacklist_link'=>route('get_edituser')))) }}
                </p>
            @else
                {{ Former::checkbox('use_blacklist', '')->text(lang('discover.form.use_blacklist'))->help(lang('discover.form.blacklist_guest_help', array('register_link'=>route('get_register'))))->disabled() }}
            @endif
        </div>

        <div class="span4">
            @if (isset($feed))
                {{ Former::primary_submit(lang('common.update')) }}
            @else
                {{ Former::primary_submit(lang('discover.form.feed.submit')) }}
            @endif
        </div>
    </div>

    <hr>
    
{{ Former::close() }}
