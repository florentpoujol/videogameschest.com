<h1>{{ lang('blog.update_post') }}</h1>

<hr>

<?php
if (isset($post)) {
    Former::populate($post);
} else echo 'Var $post not set';
?>

{{ Former::open_vertical(route('post_blog_post_update')) }}
    {{ Form::token() }}

    {{ Former::hidden('id', 0) }}

    {{ Former::text('title') }}

    {{ Former::text('title_url') }}

    {{ Former::textarea('content')->class('span8') }}

    {{ Former::submit('Update') }}

{{ Former::close() }}

