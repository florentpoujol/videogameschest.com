<?php

/*
report form via colorbox

<a href="http://the link" id="theid">link</a>
$("#theid").colorbox({iframe:true, width:"400px", height:"200px"});
 */
var_dump(Crawler::crawl_game("http://www.indiedb.com/games/bloom-memories"));
?>

<a href="{{ route('get_suggest_form') }}" id="colorbox-ajax1" data-height="100px" data-width="500px">lien</a> 
<a href="{{ route('get_report_form', array(2)) }}" id="colorbox-ajax2" data-height="500px" data-width="100px">lien</a>

@section('jQuery')
    
	$('#colorbox-ajax1').colorbox({html: function()
		{
			return this.data('height');
		}
	});


	$('#colorbox-ajax2').colorbox({iframe:true, width:$('#colorbox-ajax2').data('width'), height:$('#colorbox-ajax2').data('height')});
	
@endsection


