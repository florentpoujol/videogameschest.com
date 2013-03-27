@section('page_title')
Crawler
@endsection

<h2>RSS feeds</h1>

<hr>

<p>
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
                <a href="{{ $profile->url }}">
                @if (strpos($profile->url, 'indiedb'))
                    {{ ucfirst(url_to_name(str_replace("http://www.indiedb.com/games/", "", $profile->url))) }}
                @else
                    Click
                @endif
                </a>
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