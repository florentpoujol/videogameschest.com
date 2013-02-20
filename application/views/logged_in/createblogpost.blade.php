<h1>{{ lang('blog.write_post') }}</h1>

<hr>

{{ Former::open_vertical(route('post_blog_post_create')) }}
    {{ Form::token() }}

    {{ Former::text('title') }}

    {{ Former::text('title_url') }}

    {{ Former::textarea('content')->class('span8')->help(lang('vgc.common.markdown_help')) }}

    {{ Former::submit('Submit') }}

{{ Former::close() }}

