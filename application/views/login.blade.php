@section('page_title')
    {{ lang('login.title') }}
@endsection

<div id="login">
    <h2>{{ lang('login.title') }}</h2>

    <hr>

    @include('forms/login')

    <hr>

    <a class="accordion-toggle" data-toggle="collapse" href="#lostpassword">
        {{ lang('login.lost_password') }}
    </a> 
    
    <div id="lostpassword" class="collapse">
    	<br>
        @include('forms/lostpassword')
    </div>

</div> <!-- /#login -->
