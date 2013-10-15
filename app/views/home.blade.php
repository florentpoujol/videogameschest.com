@section('page_title')
    {{ lang('vgc.home.site_slogan') }}
@endsection

<div id="home">

    <hr>

    <div class="row">
        <div class="span12">
            <div id="slogan">
                {{ lang('vgc.home.catchphrase') }}
            </div>

            @include('suggestioncreate')
        </div>
    </div>

    <hr>

</div> <!-- /#home -->
