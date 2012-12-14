<h1>Admin Home</h1>

<p>Welcome {{ user()->dev->name }}</p>

@if (IS_ADMIN)
	@include('admin/selecteditdeveloper')
@endif

@include('admin/selecteditgame')

