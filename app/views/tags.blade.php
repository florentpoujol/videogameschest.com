@section('page_title')
    Tags
@endsection

<div class="row">
    <div class="span12">
        <h1>Tags</h1>

        <hr>
        
        {{ Former::open_vertical(route('post_tags_update')) }}
            {{ Form::token() }}

            {{ Former::success_submit('Validate') }} <br>
            <br>
            {{ Former::xlarge_text('new_tags', 'Enter new tag(s) here :')->placeholder('New tag(s) (coma seprated)') }}

            Or update/delete below : <br>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Created at - Updated at</th>
                        <th>Profile count</th>
                    </tr>
                </thead>
            
                @foreach (Tag::all() as $tag)
                <tr>
                    <td>
                        {{ $tag->id }}
                    </td>
                    <td>
                        {{ Former::text('tags['.$tag->id.']', '')->value($tag->name) }}
                    </td>
                    <td>{{ $tag->created_at }} | {{ $tag->updated_at }} </td>
                    <td>
                        {{ $tag->profiles->count() }}
                    </td>
                </tr>
                @endforeach
            </table>

            {{ Former::success_submit('Validate') }}
        {{ Former::Close() }}
    </div>
</div>