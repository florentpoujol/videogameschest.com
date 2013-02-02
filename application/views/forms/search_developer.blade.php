    <div class="row">
        <div class="span12">
        And whose games<br> <br>
        </div>
    </div>

    <?php
    $fields = array('devices', /*'operatingsystems',*/'technologies', /*'stores'*/  );
    ?>
    <div class="row testtaille">
        @include('partials.search_foreach_fields')
    </div>
