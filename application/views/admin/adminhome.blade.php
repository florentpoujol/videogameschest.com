@section('page_title')
    {{ lang('admin.home.title') }}
@endsection

<h1>{{ lang('admin.home.title') }}</h1>

<p>{{ lang('admin.home.hello') }} {{ user()->username }}</p>

<p><a href="{{ route('get_edituser') }}">{{ lang('admin.menu.edit_user_account') }}</a></p>

@if ( ! empty(user()->devs) || is_admin())
    @include('admin/selecteditdeveloper')
@endif
    
<p><a href="{{ route('get_adddeveloper') }}">{{ lang('admin.menu.add_developer') }}</a></p>


@if ( ! empty(user()->games) || is_admin())
    @include('admin/selecteditgame')
@endif

<p><a href="{{ route('get_addgame') }}">{{ lang('admin.menu.add_game') }}</a></p>


