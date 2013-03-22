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

?> 

<div id="search-page">
    <div class="pull-right muted" id="search-icon">
        {{ icon('search') }}
    </div>

    <h1>{{ lang('vgc.search.title') }} <small>{{ lang('vgc.search.subtitle') }}</small></h1>
    
    <hr>
 
    <p>
        {{ lang('vgc.search.search_help') }} <br>
        <!-- <br>
        <a class="accordion-toggle" data-toggle="collapse" href="#collapse-learn-more">
            {{ icon('circle-arrow-down') }} {{ lang('vgc.search.search_page_indepth_link') }} 
        </a> -->
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
            <?php
            $category_name = get_category_name($search_id);


            ?>
            {{ lang('vgc.search.current_category_id') }} <strong>{{ $search_id }}</strong>
            
            {{ Former::open_inline(route('post_category_name'))->rules(array('category_name' => 'required|min:5')) }}
                {{ Form::token() }}
                {{ Form::hidden('search_id', $search_id) }}

                @if ($category_name !== null)
                    You named this category 
                    {{ Former::text('category_name')->value($category_name) }}
                    {{ Former::submit(lang('vgc.common.update')) }}
                @else
                    {{ Former::text('category_name') }}
                    {{ Former::submit(lang('vgc.search.submit_give_category_name')) }}
                @endif

                {{ antiBot() }}
            {{ Former::close() }}
        </p>

        <br>
    @endif
       
    <?php
    $old = Input::old();
    $profile_type = 'game';
    $current_profile_type = 'game';
    // form populating in forms.search_profile_common
    ?> 
    
    @include('forms.search_profiles_common')
</div> <!-- /#search-profiles -->

@section('jQuery')
$('#game-tabs a:first').tab('show');
@endsection