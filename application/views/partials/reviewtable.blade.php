@if (count($profiles) > 0)
    <table class="table table-striped table-bordered">
        <tr>
            <th>{{ lang('reviews.table.profile') }}</th>
            <th></th>
        </tr>

    @foreach ($profiles as $profile)
        <tr>
            <td><a href="{{ route('get_'.$profile_type, array($profile->id)) }}">{{ $profile->name }}</a> ({{ $profile->class_name }}</td>
            <td>
                {{ Former::open('admin/reviews') }}
                    {{ Form::token() }}
                    <input type="hidden" name="review" value="{{ $review }}">
                    <input type="hidden" name="profile" value="{{ $profile_type }}">
                    <input type="hidden" name="id" value="{{ $profile->id }}">
                    <input type="submit" value="{{ lang('reviews.table.approve') }}" class="btn btn-success">
                </form>
            </td>
        </tr>
    @endforeach
    </table>
@else
    {{ lang('reviews.no_review', array('type' => $profile_type, 'review' => $review)) }}
@endif