@if (count($profiles) > 0)
    <table class="table table-striped table-bordered">
        <tr>
            <th>Name and link to profile</th>
            <th></th>
        </tr>

    @foreach ($profiles as $profile)
        <tr>
            <td><a href="{{ route('get_'.$profile_type, array($profile->id)) }}">{{ $profile->name }}</a></td>
            <td>
                <form action="admin/review" method="POST">
                    {{ Form::token() }}
                    <input type="hidden" name="review" value="{{ $review }}">
                    <input type="hidden" name="profile" value="{{ $profile_type }}">
                    <input type="hidden" name="id" value="{{ $profile->id }}">
                    <input type="submit" value="Approve" class="btn btn-success">
                </form>
            </td>
        </tr>
    @endforeach
    </table>
@else
    No {{ $profile_type }} in {{ $review }} review.
@endif