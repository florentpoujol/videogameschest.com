    <div class="row">
        <div class="span12">
        And whose games<br> <br>
        </div>
    </div>

    <?php
    $fields = array('devices', 'operatingsystems', 'technologies', 'stores'  );
    $span_size = 3;
    ?>
    <div class="row">
        @include('partials.search_foreach_fields')
    </div>
