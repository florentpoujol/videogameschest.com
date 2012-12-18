@section('page_title')
	{{ lang('admin.review.title') }}
@endsection


<h2>{{ lang('admin.review.title') }}</h2>

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

<h3>Games</h3>

<?php 
$profiles = Game::where_privacy($review)->get();
$profile_type = 'game';
?>

@include('partials.reviewtable')

<h3>Developers</h3>

<?php 
$profiles = Dev::where_privacy($review)->get();
$profile_type = 'developer'; 
?>

@include('partials.reviewtable')

