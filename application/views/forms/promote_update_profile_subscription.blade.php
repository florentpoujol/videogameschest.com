<?php
Former::populate($profile);
?>
{{ Former::open_vertical(route('post_promote_update_profile_subscription')) }}
    {{ Form::token() }}

    {{ Former::hidden('profile_type', $profile->class_name) }}
    {{ Former::hidden('profile_id', $profile->id) }}

    {{ Former::checkbox('in_promotion_feed', '')->text(lang('promote.profile_allow_promote_in_feed')) }}
    {{ Former::checkbox('in_promotion_newsletter', '')->text(lang('promote.profile_allow_promote_in_newsletter')) }}

    {{ Former::primary_submit(lang('common.update')) }}
{{ Former::close() }}
