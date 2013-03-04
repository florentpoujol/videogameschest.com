@section('page_title')
    {{ lang('vgc.browse.title') }}
@endsection

<?php
/* 
The following variables exist if the url has a search id :
$profiles
$search_data
$search_id
*/
?> 

<div id="browse">
    <div class="pull-right muted" id="search-icon">
        {{ icon('search') }}
    </div>

    <h1>{{ lang('vgc.browse.title') }}
        @if (isset($search_id))
            <small>{{ $search_id }}</small>
        @endif
    </h1>

    <hr>

    @if ( ! isset($search_id))
        <p>
            {{ lang('vgc.browse.no_search_id_help', array('search_page_url' => route('get_search_page'))) }}
        </p>
        <br>
    @endif

    <?php
    $rules = array(
        'search_id' => 'required',
    );

    if ( ! isset($search_id)) $search_id = null;
    $old = Input::old();
    if (isset($old['search_id'])) $search_id = $old['search_id'];
    ?>
    {{ Former::open_inline(route('post_browse'))->rules($rules) }}
        {{ Form::token() }}

        {{ Former::text('search_id', '')->placeholder(lang('vgc.browse.form_text_field'))->value($search_id) }}

        {{ Former::submit(lang('vgc.browse.form_submit')) }}
    {{ Former::close() }}

    @if (isset($profiles))
        <hr>

        @include('partials.profile_list_tiles')


    @endif
    


</div> <!-- /#browse -->
