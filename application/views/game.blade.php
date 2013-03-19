@section('page_title')
	{{ $profile->name }}
@endsection

<div id="game" class="profile-page">
	<h1>Game profile <small>{{ $profile->name }}</small></h1>

	<p>
		bla
	</p>

	<?php $model = false ?>
	@include('forms/postreport')
</div>

