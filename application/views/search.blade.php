@section('page_title')
    {{ lang('search.title') }}
@endsection

<?php 
$old = Input::old();
if ( ! empty($old)) Former::populate($old);

if ( ! isset($search_data)) $search_data = array();
if ( ! empty($search_data)) Former::populate($search_data);

//var_dump($search_data);

$default_tab = 'developer';
if (isset($old['class'])) $default_tab = $old['class'];
if (isset($search_data['class'])) $default_tab = $search_data['class'];
?> 

<div id="search-profiles">
    <h1>{{ lang('search.title') }} <small>{{ lang('search.subtitle') }}</small></h1>
    
    <hr>
    
    @if (isset($search_id))
        <p>
            <a href="{{ route('get_search_feed', array('rss', $search_id)) }}" title="{{ lang('search.rss_feed') }}">{{ icon('rss') }} {{ lang('search.rss_feed') }}</a>
        </p>

        <br>
    @endif
        
    <div class="accordion" id="search-form-accordion">
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="search-form-accordion" href="#collapse1">
                    {{ lang('search.hide_show_link') }}
                </a>
            </div>
<?php
if (isset($profiles)) $accordion_default_state = 'out';
else $accordion_default_state = 'in';
?>

            <div id="collapse1" class="accordion-body collapse {{ $accordion_default_state }}">
                <div class="accordion-inner">
                    @include('partials.search_form')
                </div>
            </div> <!-- /.accordion-body -->
        </div> <!-- /.accordion-group -->
    </div> <!-- /.accordion #search-form-accordion -->

</div> <!-- /#search-profiles -->

@if (isset($profiles))
    <hr>

    <?php
    $count = count($profiles);
    ?>
    @if ($count <= 0)
        <p id="profile-list">
            {{ lang('search.no_profile_found') }}
        </p>
    @else
        <p id="profile-list">
            {{ lang('search.profiles_found', array('num'=>$count)) }}
        </p>

        @include('partials.profile_list_tiles')
    @endif
@endif 
{{-- if (isset($profiles)) --}}


@section('jQuery')
$('#search_tabs a[href="#{{ $default_tab }}-tab"]').tab('show');
$('#array_items_dev_tabs a:first').tab('show');
@endsection