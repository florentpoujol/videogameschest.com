@section('page_title')
    Crawler
@endsection

<div class="row">
    <div class="span12">
        <h2>RSS feeds</h1>

        <hr>

        <?php
        $actions = array(
            '' => 'Choose',
            'update' => 'Update URL',
            'delete' => 'Delete',
            'read' => 'Read',
        );
        $feeds = SuggestionFeed::all();
        ?>
        {{ Former::open_vertical(route('post_suggestion_feeds_update')) }}
            {{ Form::token() }}

            {{ Former::primary_submit('Read All feeds')->name("read_all_feeds") }}
            <hr>
            {{ Former::text('new_feed_url', '')->placeholder('New feed') }} {{ Former::success_submit('Add this new feed')->name("add_new_feed") }}
            <hr>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Url</th>
                        <th>Last Read At</th>
                        <th>{{ Former::warning_submit("Do actions")->name('do_actions') }}</th>
                    </tr>
                </thead>
            @foreach ($feeds as $feed)
                <tr>
                    <td>
                        {{ Former::text('feeds['.$feed->id.'][url]', '')->value($feed->url) }}
                    </td>
                    <td>
                        {{ $feed->last_read_at }}
                    </td>
                    <td>
                        {{ Former::select('feeds['.$feed->id.'][action]', '')->options($actions) }}
                    </td>
                </tr>
            @endforeach
            </table>

        {{ Former::Close() }}

        <hr>

        <h2>Suggestions</h2>

        <?php
        $suggestions = Suggestion::whereSource('user')->get();
        $suggestions = $suggestions->merge( Suggestion::where('source', '!=', 'user')->get() );
        ?>

        {{ Former::open_vertical(route('post_suggestions_update')) }}
            {{ Form::token() }}

            <br>
            
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>url</th>
                        <th>source</th>
                        <th>created</th>

                        <th>{{ Former::success_submit("Crawl")->name('_crawl') }}</th>
                        <th>{{ Former::warning_submit("Delete")->name('_delete') }}</th>
                    </tr>
                </thead>
            @foreach ($suggestions as $suggestion)
                <tr>
                    <td>{{ $suggestion->id }}</td>
                    <td>
                        <a href="{{ $suggestion->url }}">
                            {{ substr(str_replace("http://www.", "", $suggestion->url), 0, 40) }}
                        </a>
                        {{ Former::text('suggestions_urls_by_id['.$suggestion->id.']', '')->value($suggestion->url) }}
                    </td>
                    <td>
                        @if ( is_int( strpos($suggestion->source, 'http') ) )
                            <?php
                            $matches = array();
                            preg_match("#^https?://([^/]+)#", $suggestion->source, $matches );
                            if ( ! isset($matches[1]))
                                $matches[1] = $suggestion->source
                            ?>
                            {{ $matches[1] }}
                        @else
                            {{ $suggestion->source }}
                        @endif
                    </td>
                    <td>{{ $suggestion->created_at }}</td>

                    <td><input type="radio" name="suggestion_id_to_crawl" value="{{ $suggestion->id }}"></td>
                    <td><input type="checkbox" name="suggestions_ids_to_delete[]" value="{{ $suggestion->id }}"></td>
                </tr>
            @endforeach
            </table>

            {{ Former::primary_submit('Submit actions') }}

        {{ Former::close() }}

    </div>
</div>