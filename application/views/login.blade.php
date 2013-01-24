@section('page_title')
    {{ lang('login.title') }}
@endsection

<div id="login">
    <h2>{{ lang('login.title') }}</h2>

    <hr>

    @include('forms/login')

    <hr>

    <a href="{{ route('get_lostpassword') }}">{{ lang('login.lost_password') }}</a>
</div> <!-- /#login -->
