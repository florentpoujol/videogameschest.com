    <div class="row">
        <div class="span12">
             And which ...
            <ul class="nav nav-tabs pull-right" id="search-tabs">
                <li><a href="#result-pane" data-toggle="tab">{{ lang('search.results') }}</a></li>
            </ul>
        <br> <br>
        </div>
    </div>

    <?php
    $fields = array('devices', 'operatingsystems','technologies', 'stores'  );
    ?>
    <div class="row">
        @include('partials.search_foreach_fields')
    </div>

    <?php
    $fields = array('genres', 'looks', 'periods', 'tags', 'viewpoints', 'nbplayers', 'languages', );
    ?>
    <div class="row">
        
        @include('partials.search_foreach_fields')
    </div>
