@section('page_title')
    {{ lang('search.title') }}
@endsection

<?php 
$old = Input::old();
if ( ! empty($old)) Former::populate($old);

if ( ! isset($search_data)) $search_data = array();
if ( ! empty($search_data)) Former::populate($search_data);

//var_dump($search_data);

$default_tab = Config::get('vgc.search.default_tab');
if (isset($old['profile_type'])) $default_tab = $old['profile_type'];
//if (isset($search_data['profile_type'])) $default_tab = $search_data['profile_type'];

 $results_disabled = 'disabled';
if (isset($search_id) && isset($profiles)) {
    $results_disabled = '';
    $default_tab = 'results';
}

?> 

<div id="search-profiles">
    <div class="pull-right muted" id="search-icon">
        {{ icon('search') }}
    </div>

    <h1>{{ lang('search.title') }} <!-- <small>{{ lang('search.subtitle') }}</small> --></h1>
    
    <hr>
    
    @if (isset($search_id))
        <p>
            <a href="{{ route('get_search_feed', array($search_id)) }}" title="{{ lang('search.rss_feed') }}">{{ icon('rss') }} {{ lang('search.rss_feed') }}</a> | {{ lang('search.search_id') }} : {{ $search_id }} {{ tooltip(lang('search.search_id_uses')) }}
        </p>

        <br>
    @endif
    
    <ul class="nav nav-tabs" id="search-tabs">
        @if ($default_tab == 'result')
            <li><a href="#results-pane" data-toggle="tab">{{ lang('search.results') }}</a></li>
        @else
            <li class="{{ $results_disabled }}"><a href="#results-pane">{{ lang('search.results') }}</a></li>
        @endif

        @foreach (get_profiles_types() as $profile_type)
            <li><a href="#{{ $profile_type }}-pane" data-toggle="tab">{{ lang('common.'.$profile_type) }}</a></li>
        @endforeach
    </ul>

    <div class="tab-content">
        @if (isset($profiles))
        <div class="tab-pane" id="results-pane">
            
                <?php
                $count = count($profiles);
                ?>
                <p id="profile-list">

                @if ($count <= 0)
                        {{ lang('search.no_profile_found') }}
                    </p>
                @else
                        {{ lang('search.profiles_found', array('num'=>$count)) }}
                    </p>

                    @include('partials.profile_list_tiles')
                @endif
             
        </div> <!-- /#results-pane .tab-pane -->
        @endif
        @foreach (get_profiles_types() as $profile_type)
            <div class="tab-pane" id="{{ $profile_type }}-pane">
                @include('forms.search_profiles_common')
                
            </div> <!-- /#{{ $profile_type }}-pane .tab-pane -->
        @endforeach
    </div> <!-- /.tab-content -->
</div> <!-- /#search-profiles -->



@section('jQuery')
$('#search-tabs a[href="#{{ $default_tab }}-pane"]').tab('show');
$('#array_items_dev_tabs a:first').tab('show');
@endsection