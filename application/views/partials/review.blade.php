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