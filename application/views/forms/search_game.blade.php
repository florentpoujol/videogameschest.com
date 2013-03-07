    <div class="row">
        <div class="span12">
            And which ...
        </div>
    </div>

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

