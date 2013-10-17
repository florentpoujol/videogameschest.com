@section('page_title')
    Crawler
@endsection

<div class="row">
    <div class="span12">
        <h2>Suggestion feeds</h1>

        <a class="accordion-toggle" data-toggle="collapse" href="#collapse-report">
            Expand...
        </a>
        <div id="collapse-report" class="collapse">
            <div class="accordion-inner">
                {{ Former::open_vertical(route('post_suggestion_feeds_update')) }}
                    {{ Form::token() }}

                    {{ Former::text('new_feed_url', '')->placeholder('New RSS or Atom feed URL') }} {{ Former::success_submit('Add this new feed')->name("add_new_feed") }}
                    <hr>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Url {{ Former::warning_submit("Update or delete")->name('update') }}</th>
                                <th>Last Read At</th>
                                <th>
                                    {{ Former::info_submit("Read selected")->name('read') }}
                                    {{ Former::primary_submit('Read all')->name("read_all_feeds") }}
                                </th>
                            </tr>
                        </thead>
                
                    @foreach (SuggestionFeed::all() as $feed)
                        <tr>
                            <td>
                                {{ $feed->id }}
                            </td>
                            <td>
                                {{ Former::text('feeds['.$feed->id.'][url]', '')->value($feed->url) }}
                            </td>
                            <td>
                                {{ $feed->last_read_at }}
                            </td>
                            <td>
                                {{ Former::checkbox('feeds['.$feed->id.'][read]', '') }}
                            </td>
                        </tr>
                    @endforeach
                    </table>

                {{ Former::Close() }}
            </div>
        </div>

        <hr>

        <h2>Suggestions</h2>

        <?php
        $status = array(
            'waiting' => 'Waiting',
            'added-manually' => 'Added manually',
            'added-by-crawler' => 'Added by crawler',
            'discarded' => 'Discarded',
            'delete' => 'Delete',
        );
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
                        <th>Data</th>
                        <th>status {{ Former::info_submit("Update")->name('update_status') }}</th>
                        <th>{{ Former::success_submit("Crawl")->name('crawl') }}</th>
                    </tr>
                </thead>
            @foreach ($suggestions as $suggestion)
                <tr>
                    <td>{{ $suggestion->id }}</td>
                    <td>
                        <a href="{{ $suggestion->url }}" title="{{ $suggestion->url }}">
                            @if ($suggestion->title != '')
                                {{ $suggestion->title }}
                            @else
                                {{ $suggestion->url }}
                            @endif                            
                        </a> <br>

                        @if( $suggestion->profile_id != 0)
                            Profile : <a href="{{ route('get_profile_view', array($suggestion->profile->id)) }}">{{ $suggestion->profile->name }}</a> (<a href="{{ route('get_profile_update', array($suggestion->profile->id)) }}">Update</a>)<br>
                        @endif

                        Source : {{ $suggestion->source }} <br>

                        @if( $suggestion->guid != '')
                            Guid : {{ $suggestion->guid }} <br>
                        @endif

                        Created at : {{ $suggestion->created_at }}
                    </td>
                    <td>
                        {{ Former::select('status_by_ids['.$suggestion->id.']', '')->options($status)->value($suggestion->status) }}
                    </td>
                    <td><input type="radio" name="suggestion_id_to_crawl" value="{{ $suggestion->id }}"></td>
                </tr>
            @endforeach
            </table>

            {{ Former::primary_submit('Submit actions') }}

        {{ Former::close() }}

    </div>
</div>