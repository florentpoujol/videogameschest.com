@section('page_title')
	{{ lang('vgc.review.title') }}
@endsection

<div class="review">
    <h1>{{ lang('vgc.review.title') }}</h1>

    <hr>

    <p>
        <a href="{{ route('get_review_feed', array(user_id(), user()->url_key)) }}" title="{{ lang('vgc.review.rss_feed') }}">{{ icon('rss') }} {{ lang('vgc.review.rss_feed') }}</a>
    </p>
    <br>

    <?php
    $preview_profiles = PreviewProfile::where_privacy($review)->order_by('created_at', 'asc')->get();
    ?>

    @if ( ! empty($preview_profiles))
        {{ Former::open(route('post_reviews')) }}
            {{ Form::token() }}
            {{ Form::hidden('review_type', $review) }}

            <table class="table table-striped table-bordered">
                <tr>
                    <th>{{ lang('vgc.review.table.profile') }}</th>
                    <th><input type="submit" value="{{ lang('vgc.review.table.approve') }}" class="btn btn-success"></th>
                </tr>

                @foreach ($preview_profiles as $profile)
                    <tr>
                        <td>
                            <a href="{{ route('get_profile_preview', array($profile->type, $profile->id)) }}" title="Preview">{{ $profile->name }}</a> <a href="{{ route('get_profile_view', array($profile->type, name_to_url($profile->name))) }}">Current version</a>
                        </td>
                        
                        <td>
                            
                            <input type="checkbox" name="approved_profiles[{{ $profile->type }}][]" value="{{ $profile->id }}">
                        </td>
                    </tr>
                @endforeach
            </table>
        </form>
    @else
        {{ lang('vgc.review.no_profile_in_review') }}
    @endif
</div>
