@section('page_title')
    Private profiles
@endsection

<div>
    <h1>Private profiles</h1>

    <hr>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Created at</th>
            </tr>
        </thead>

        @foreach (Profile::all() as $profile)
            <tr>
                <td>
                    {{ $profile->id }}
                </td>

                <td>
                    <a href="{{ route('get_profile_view', array($profile->id)) }}">{{ $profile->name }}</a> 
                    (<a href="{{ route('get_profile_update', array($profile->id)) }}">Update</a>)
                </td>

                <td>
                    {{ $profile->created_at }}
                </td>
            </tr>
        @endforeach
    </table>
</div>
