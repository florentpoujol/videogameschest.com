<?php

/*
report form via colorbox

<a href="#" id="link">link</a>
$("#link").colorbox({href:"{{ route('get_report_form', array($profile->id)) }}" });
 */

?>


@section('cssfiles')
   {{ Asset::container('colorbox')->styles() }}
@endsection 
@section('jsfiles')
   {{ Asset::container('colorbox')->scripts() }}
@endsection 

@section('jQuery')
    

@endsection


