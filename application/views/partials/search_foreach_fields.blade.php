@foreach ($fields as $field)
    <?php
    $values = array();
    //if (isset($old['arrayitems'][$field])) $values = $old['arrayitems'][$field];

    if (! isset($span_size)) $span_size = 3;
    ?>
    <div class="span{{ $span_size }}">
        @include('partials.search_array_field')
    </div>
@endforeach
