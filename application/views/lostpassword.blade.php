@section('page_title')
    {{ lang('lostpassword.title') }}
@endsection

<div id="lostpassword">
    <p>
        {{ lang('lostpassword.help') }}
    </p>

    @include('forms/lostpassword')
</div> <!-- /#lostpassword -->
