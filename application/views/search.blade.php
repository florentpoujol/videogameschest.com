@section('page_title')
    {{ lang('search.title') }}
@endsection

<?php
/* 
The following variables exist if the url has a search id :
$profiles
$search_data
$search_id
*/
?> 

<div id="search-profiles">
    <div class="pull-right muted" id="search-icon">
        {{ icon('search') }}
    </div>

    <h1>{{ lang('search.title') }}

        @if (isset($search_id))
            <small>{{ $search_id }}</small>
        @endif
    </h1>
    
    <hr>
    
    @if (isset($search_id))
        <div class="pull-right">
            <a href="{{ route('get_search_feed', array($search_id)) }}" title="{{ lang('search.rss_feed') }}">{{ icon('rss') }} {{ lang('search.rss_feed') }}</a><br>
        </div>

        <p>
            {{ lang('search.search_id') }} <strong>{{ $search_id }}</strong> <br>
            <br>
            {{ lang('search.search_id_help') }} <br>
        </p>

        <div class="align-center">
            <a href="{{ route('get_browse_page') }}" class="btn btn-primary">{{ lang('search.browse_this_search') }}</a> or 
            <a href="{{ route('get_discover_page') }}" title="" class="btn btn-primary">{{ lang('discover.title') }}</a> 
            {{ lang('search.browse_discover_buttons_text') }}
        </div>

    @else
        <p>
            {{ lang('search.no_search_id_help') }} 
        </p>
    @endif

    <hr>
    
    <ul class="nav nav-tabs" id="main-tabs">
        @foreach (get_profile_types() as $profile_type)
            <li><a href="#{{ $profile_type }}-pane" data-toggle="tab">{{ lang('common.'.$profile_type) }}</a></li>
        @endforeach
    </ul>

    <div class="tab-content">      
        <?php
        $old = Input::old();
        // form populating in forms.search_profile_common
        ?> 
        @foreach (get_profile_types() as $profile_type)
            <div class="tab-pane" id="{{ $profile_type }}-pane">
                @include('forms.search_profiles_common')
            </div> <!-- /#{{ $profile_type }}-pane .tab-pane -->
        @endforeach
    </div> <!-- /.tab-content -->
</div> <!-- /#search-profiles -->

@section('jQuery')
$('#main-tabs a:first').tab('show');
$('#game-tabs a:first').tab('show');
@endsection