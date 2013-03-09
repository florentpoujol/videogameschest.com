@section('page_title')
Crawler
@endsection

<h1>Crawler</h1>

<hr>

<p>
    {{ Former::open_inline(route('post_crawler_add_feed_link')) }}
        {{ Form::token() }}
        Add a link to the RSS feed links : 
        {{ Former::text('feed_link', 'Add a link to the RSS feed links') }}
        {{ Former::submit() }}
    {{ Former::close() }}
    <br>
    LInks :
    <br>
    @foreach (json_decode(DBConfig::get('crawler_feed_links'), true) as $link)
        {{ $link }} <br>
    @endforeach
    <br>
    <a href="{{ route('get_crawler_read_feed_links') }}" class="btn btn-primary">Extract links from RSS links</a> <br>
</p>

<hr>

<p>
    <a href="{{ route('get_crawler_auto') }}" class="btn btn-primary">Start the auto crawling</a> <br>
    <br>
    Profiles to crawl :
</p>
<br>
<?php
$profiles = ProfilesToCrawl::order_by('profile_id', 'asc')->order_by('created_at', 'asc')->get();
?>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Link</th>
            <th>profile type</th>
            <th>profile ID</th>
            <th>created at</th>
            <th>updated at</th>
        </tr>
    </thead>
@foreach ($profiles as $profile)
    <tr>
        <td>{{ $profile->id }}</td>
        <td>{{ $profile->link }}</td>
        <td>{{ $profile->profile_type }}</td>
        <td>
            {{ $profile->profile_id }}
            
            @if ($profile->profile_id != 0)
                <a href="{{ route('get_profile_preview', array($profile->profile_type, $profile->profile_id)) }}">Preview</a>
            @endif
        </td>
        
        <td>{{ $profile->created_at }}</td>
        <td>{{ $profile->updated_at }}</td>
    </tr>
@endforeach
</table>