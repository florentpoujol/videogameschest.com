@layout('layouts.main2')

@section('page_title')
	{{ Lang('admin.review.title') }}
@endsection

@section('page_content')
	<?php
	echo 
	Navigation::tabs(array(
		array(
			'url' => route('get_reviews', array('submission')),
			'label' => lang('admin.review.submission_title'),
		),
		array(
			'url' => route('get_reviews', array('publishing')),
			'label' => lang('admin.review.publishing_title'),
		),
	));
	?>
	
	<h2>Games</h2>

	<?php 
	$profiles = Game::where('privacy', '=', 'in_'.$review.'_review')->get();
	$profile_type = 'game';
	?>

	@include('partials.reviewtable')

	<h2>Developers</h2>

	<?php 
	$profiles = Dev::where('privacy', '=', 'in_'.$review.'_review')->get('id', 'name');
	$profile_type = 'dev'; 
	?>

	@include('partials.reviewtable')
@endsection
