@section('page_title')
	{{ lang('reviews.title') }}
@endsection

<div class="peerreview">
    <h1>{{ lang('reviews.title') }}</h1>

    <hr>

    <?php
    echo 
    Navigation::tabs(array(
    	array(
    		'url' => route('get_reviews', array('submission')),
    		'label' => lang('reviews.submission_title'),
            //active
    	),
    	array(
    		'url' => route('get_reviews', array('publishing')),
    		'label' => lang('reviews.publishing_title'),
    	),
    ));
    ?>

    <p>
        <a href="{{ route('get_reviews_feed', array($review, user_id(), user()->url_key)) }}" title="{{ lang('reviews.rss_feed', array('review'=>$review)) }}">{{ icon('rss') }} {{ lang('reviews.rss_feed', array('review'=>$review)) }}</a>
    </p>
    <br>

    <?php 
    $profiles = Game::where_privacy($review)->order_by('created_at', 'asc')->get();
    $profiles = array_merge($profiles, Dev::where_privacy($review)->order_by('created_at', 'asc')->get());
    ?>

    @if ( ! empty($profiles))
        {{ Former::open(route('post_reviews')) }}
            {{ Form::token() }}
            {{ Form::hidden('review_type', $review) }}

            <table class="table table-striped table-bordered">
                <tr>
                    <th>{{ lang('reviews.table.profile') }}</th>
                    <th><input type="submit" value="{{ lang('reviews.table.approve') }}" class="btn btn-success"></th>
                </tr>

                @foreach ($profiles as $profile)
                    <?php
                    if ( ! in_array(user_id(), $profile->approved_by)):
                        $class = '';
                        if (is_admin() && count($profile->reports('admin')) > 0) $class = 'class="error"'; // empty() maked the page reload infinitely ?
                    ?>
                        <tr {{ $class }}>
                            <td>
                                <a href="{{ route('get_'.$profile->class_name, array(name_to_url($profile->name))) }}">{{ $profile->name }}</a> ({{ $profile->class_name }})
                            </td>
                            
                            <td>
                                
                                <input type="checkbox" name="approved_profiles[{{ $profile->class_name }}][]" value="{{ $profile->id }}">
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </form>
    @else
        {{ lang('reviews.no_review', array('review' => $review)) }}
    @endif
</div>
