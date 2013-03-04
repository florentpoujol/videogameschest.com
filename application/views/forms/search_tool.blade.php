    <div class="row">
        <div class="span12">
        <br> <br>
        </div>
    </div>

    <?php
    $fields = Tool::$array_fields;
    $span_size = 3;
    ?>
    <div class="row">
        @include('partials.search_foreach_fields')
    </div>
