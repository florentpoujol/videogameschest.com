@section('page_title')
Crawler
@endsection

<h2>RSS feeds</h1>

<hr>

<p>
    {{ Former::open_inline(route('post_crawler_add_feed_url')) }}
        {{ Form::token() }}
        Add a url to the RSS feed urls : 
        {{ Former::text('feed_url', 'Add a url to the RSS feed urls') }}
        {{ Former::submit() }}
    {{ Former::close() }}
    <ul>
    @foreach (json_decode(DBConfig::get('crawler_feed_urls'), true) as $url)
        <li>{{ $url }} </li>
    @endforeach
    </ul>
    <a href="{{ route('get_crawler_read_feed_urls') }}" class="btn btn-primary">Read the feeds</a> <br>
</p>

<hr>

<h2>Suggested Profiles</h2>

<?php
$profiles = array_merge(
    SuggestedProfile::where_statut('waiting')->where_source('user')->order_by('created_at', 'asc')->get(),
    SuggestedProfile::where_statut('waiting')->where_source('rss')->order_by('created_at', 'asc')->get()
);

$actions = array(
    '' => 'Choose',
    'crawl' => 'Crawl',
    'manually-added' => 'Mannually added',
    'discard' => 'Discard',
    'delete' => 'DELETE',
);
?>

{{ Former::open_vertical(route('post_crawler_perform_actions')) }}
    {{ Form::token() }}
    {{ Former::primary_submit('Submit actions') }}
    <br>
    
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>url</th>
                <th>Action</th>
                <th>statut</th>
                <th>source</th>
                <th>created</th>
            </tr>
        </thead>
    @foreach ($profiles as $profile)
        <tr>
            <td>{{ $profile->id }}</td>
            <td> 
                <a href="{{ $profile->url }}">Click</a>
                {{ Former::text('profiles['.$profile->id.'][url]', '')->value($profile->url) }}
            </td>
            <td>
                {{ Former::select('profiles['.$profile->id.'][action]', '')->options($actions) }}
            </td>
            <td>{{ $profile->statut }}</td>
            <td>{{ $profile->source }}</td>
            <td>{{ $profile->created_at }}</td>
        </tr>
    @endforeach
    </table>

    {{ Former::primary_submit('Submit actions') }}

{{ Former::close() }}