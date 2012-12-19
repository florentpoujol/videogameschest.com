@section('page_title')
	{{ lang('reviews.title') }}
@endsection


<h2>{{ lang('reviews.title') }}</h2>

<?php
echo 
Navigation::tabs(array(
	array(
		'url' => route('get_reviews', array('submission')),
		'label' => lang('reviews.submission_title'),
	),
	array(
		'url' => route('get_reviews', array('publishing')),
		'label' => lang('reviews.publishing_title'),
	),
));
?>

<!-- <h3>{{ lang('reviews.game_title') }}</h3> -->

<?php 
$profiles = Game::where_privacy($review)->order_by('created_at', 'asc')->get();
$profiles = array_merge($profiles, Dev::where_privacy($review)->order_by('created_at', 'asc')->get());
?>

@if (count($profiles) > 0)
{{ Former::open('admin/reviews') }}
{{ Form::token() }}

    <table class="table table-striped table-bordered">
        <tr>
            <th>{{ lang('reviews.table.profile') }}</th>
            <th><input type="submit" value="{{ lang('reviews.table.approve') }}" class="btn btn-success"></th>
        </tr>

        @foreach ($profiles as $profile)
            <?php
            
            if ( ! in_array(USER_ID, $profile->approved_by)):
                $class = '';
                if (IS_ADMIN && count($profile->reports('admin')) > 0) $class = 'class="error"'; // empty() maked the page reload infinitely ?
            ?>
                <tr {{ $class }}>
                    <td>
                        <a href="{{ route('get_'.$profile->class_name, array($profile->id)) }}">{{ $profile->name }}</a> ({{ $profile->class_name }})
                    </td>
                    
                    <td>
                        {{ Former::hidden('profile_type', $profile->class_name) }}
                        <input type="checkbox" name="approved_profiles[]" value="{{ $profile->id }}">
                    </td>
                </tr>
            @endif
        @endforeach

    </table>

</form>

@else
    {{ lang('reviews.no_review', array('review' => $review)) }}
@endif
