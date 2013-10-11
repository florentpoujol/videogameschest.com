@section('page_title')
    {{ lang('lostpassword.title') }}
@endsection

<div id="lostpassword">
    <h3>{{ lang('lostpassword.title') }}</h3>

    <hr>

    <p>
        {{ lang('lostpassword.help') }}
    </p>

    @include('forms/lostpassword')
</div> <!-- /#lostpassword -->
