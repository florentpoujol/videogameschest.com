@section('page_title')
    {{ lang('admin.home.title') }}
@endsection

<div id="admin-home">
    <h1>{{ lang('admin.home.title') }}</h1>

    <p>{{ lang('admin.home.hello') }} {{ user()->username }}</p>

    <ul class="unstyled">
        @if (is_admin())
            <li><a href="{{ route('get_adduser') }}">Add a user</a></li>
        @endif
        <li><a href="{{ route('get_edituser') }}">{{ lang('admin.menu.edit_user_account') }}</a></li>


        <li><a href="{{ route('get_adddeveloper') }}">{{ lang('admin.menu.add_developer') }}</a></li>
        @if ( ! empty(user()->devs) || is_admin())
            <li>
                <a data-toggle="collapse" href="#collapse3">{{ lang('admin.menu.edit_developer') }} {{ icon('caret-down') }}</a>
                
                <div id="collapse3" class="collapse">
                    <blockquote>
                        @include('admin/selecteditdeveloper')
                    </blockquote>
                </div>
            </li>
        @endif


        <li><a href="{{ route('get_addgame') }}">{{ lang('admin.menu.add_game') }}</a></li>
        @if ( ! empty(user()->devs) || is_admin())
            <li>
                <a data-toggle="collapse" href="#collapse2">{{ lang('admin.menu.edit_game') }} {{ icon('caret-down') }}</a>
                
                <div id="collapse2" class="collapse">
                    <blockquote>
                        @include('admin/selecteditgame')
                    </blockquote>
                </div>
            </li>
        @endif


        @if (is_admin())
            <li><a href="{{ route('get_reviews') }}">{{ lang('reviews.title') }}</a></li>
        @endif
        @if ( ! empty(user()->devs) || ! empty(user()->games) || is_admin())
            <li><a href="{{ route('get_reports') }}">{{ lang('reports.title') }}</a></li>
        @endif
        

        <li><a href="{{ route('get_logout') }}">{{ lang('menu.logout') }}</a></li>
    </ul>
</div>
