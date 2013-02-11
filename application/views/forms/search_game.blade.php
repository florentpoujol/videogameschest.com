    <div class="row">
        <div class="span12">
            And which ...
        </div>
    </div>
<!-- 
    <?php
    $fields = array('devices',  'genres', 'stores', 'technologies'  );
    $span_size = 3;
    ?>
    <div class="row">
        
    </div>

    <hr>

    <?php
    $fields = array( 'tags', 'looks', 'periods', 'viewpoints',   );
    $span_size = 3;
    ?>
    <div class="row">
        
    </div>

    <hr>

    <?php
    $fields = array( 'nbplayers', 'languages', 'operatingsystems', );
    $span_size = 3;
    ?>
    <div class="row">
        
    </div> -->


    <hr>

    <?php
    $fields = Game::$array_fields;
    $fields[] = 'stores';
    sort($fields);
    ?>

    <ul class="nav nav-tabs" id="game-tabs">
        @foreach ($fields as $field)
            <li><a href="#{{ $field }}-pane" data-toggle="tab">{{ lang($field.'.title') }}</a></li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach ($fields as $field)
            <div class="tab-pane" id="{{ $field }}-pane">
                @include('partials/search_array_field')
            </div>
        @endforeach
    </div>

