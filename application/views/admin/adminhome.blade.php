<h1>Admin Home</h1>

<p>Welcome {{ user()->username }}</p>

@if (IS_ADMIN)
	@include('admin/selecteditdeveloper')
@endif

@include('admin/selecteditgame')

