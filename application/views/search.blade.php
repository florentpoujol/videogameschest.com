@section('page_title')
    {{ lang('vgc.search.title') }}
@endsection

<?php
/* 
The following variables exist if the url has a search id :
$profiles
$search_data
$search_id
*/

$current_profile_type = 'game';
if (isset($search_data) && isset($search_data['profile_type'])) {
    $current_profile_type = $search_data['profile_type'];
}
?> 

<div id="search-profiles">
    <div class="pull-right muted" id="search-icon">
        {{ icon('search') }}
    </div>

    <h1>{{ lang('vgc.search.title') }}

        @if (isset($search_id))
            <small>{{ $search_id }}</small>
        @endif
    </h1>
    
    <hr>
 
    <p>
        {{ lang('vgc.search.search_page_quick_help') }} <br>
        <br>
        <a class="accordion-toggle" data-toggle="collapse" href="#collapse-learn-more">
            {{ icon('circle-arrow-down') }} {{ lang('vgc.search.search_page_indepth_link') }} 
        </a>
    </p> 

    <div id="collapse-learn-more" class="collapse">
        {{ lang('vgc.search.search_page_indepth_help') }}
    </div>

    <hr>
  
    @if (isset($search_id))
        
        <div class="pull-right">
            <a href="{{ route('get_search_feed', array($search_id)) }}" title="{{ lang('vgc.search.rss_feed') }}">{{ icon('rss') }} {{ lang('vgc.search.rss_feed') }}</a><br>
        </div>

        <p>
            {{ lang('vgc.search.search_id') }} <strong>{{ $search_id }}</strong> <br>
        </p>

        <br>
    @endif

    
    
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
$('#main-tabs a[href="#{{ $current_profile_type }}-pane"]').tab('show');
$('#game-tabs a:first').tab('show');
@endsection