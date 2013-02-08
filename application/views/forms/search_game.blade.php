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
                <?php 
                echo Former::radios('', lang('search.'.$field.'_help'))->radios(array(
                    lang('common.array_field_where_all') => array(
                        'value' => 'all',
                        'name' => $field.'_where',
                        'id' => $profile_type.'_'.$field.'_array_field_all',
                     ),

                    lang('common.array_field_where_any') => array(
                        'value' => 'any',
                        'name' => $field.'_where',
                        'id' => $profile_type.'_'.$field.'_array_field_any',
                        'checked' => 'checked'
                    ),
                ));
                ?>

                {{ array_to_checkboxes($field, array(), 'arrayitems['.$field.'][]') }}
            </div>
        @endforeach
    </div>


    