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
        <a href="{{ route('get_reviews_feed', array($review, user_id(), user()->url_key)) }}" title="{{ lang('reviews.rss_feed', array('review' => $review)) }}">{{ icon('rss') }} {{ lang('reviews.rss_feed', array('review'=>$review)) }}</a>
    </p>
    <br>

    <?php 
    // $profiles = Game::where_privacy($review)->order_by('created_at', 'asc')->get();
    // $profiles = array_merge($profiles, Dev::where_privacy($review)->order_by('created_at', 'asc')->get());
    $preview_profiles = PreviewProfile::where_privacy($review)->order_by('created_at', 'asc')->get();
    ?>

    @if ( ! empty($preview_profiles))
        {{ Former::open(route('post_reviews')) }}
            {{ Form::token() }}
            {{ Form::hidden('review_type', $review) }}

            <table class="table table-striped table-bordered">
                <tr>
                    <th>{{ lang('reviews.table.profile') }}</th>
                    <th><input type="submit" value="{{ lang('reviews.table.approve') }}" class="btn btn-success"></th>
                </tr>

                @foreach ($preview_profiles as $preview_profile)
                    <?php
                    $profile = $preview_profile->public_profile;
                    // dd($preview_profile);
                    ?>
                    <tr>
                        <td>
                            <a href="{{ route('get_profile_preview', array($profile->type, $profile->id)) }}" title="Preview">{{ $profile->name }}</a> ({{ $profile->type }}) <a href="{{ route('get_profile_view', array($profile->type, name_to_url($profile->name))) }}">Current version</a>
                        </td>
                        
                        <td>
                            
                            <input type="checkbox" name="approved_profiles[{{ $profile->type }}][]" value="{{ $profile->id }}">
                        </td>
                    </tr>
                @endforeach
            </table>
        </form>
    @else
        {{ lang('reviews.no_review', array('review' => $review)) }}
    @endif
</div>
