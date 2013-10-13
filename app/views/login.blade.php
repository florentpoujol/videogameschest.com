@section('page_title')
    {{ lang('login.title') }}
@endsection

<div id="login">
    <h2>{{ lang('login.title') }}</h2>

    <hr>

    <?php
    $rules = array(
        'username' => 'required|min:2',
        'password' => 'required|min:5',
    );
    ?>
    {{ Former::open_vertical(route('post_login'))->rules($rules) }}
        {{ Form::token() }}
        
        {{ Former::text('username', '')->placeholder(lang('login.name_label')) }}
        
        {{ Former::password('password', '')->placeholder(lang('login.password_label')) }}

        {{ Former::checkbox('keep_logged_in', '')->text(lang('login.keep_logged_in_label'))->check() }}

        {{ Former::primary_submit(lang('login.submit')) }}
    {{ Former::close() }}

    <hr>

    <a class="accordion-toggle" data-toggle="collapse" href="#lostpassword">
        {{ lang('login.lost_password') }}
    </a> 
    
    <div id="lostpassword" class="collapse">
    	<br>
        <?php
        $rules = array(
            'lost_password_username' => 'required|min:2',
        );
        ?>
        {{ Former::open_vertical(route('post_lostpassword'))->rules($rules) }}
            {{ Form::token() }}

            {{ Former::text('lost_password_username', '')->placeholder(lang('login.name_label')) }} 

            {{ antiBot() }}

            {{ Former::primary_submit(lang('common.submit')) }}
        {{ Former::close() }}
    </div>

</div> <!-- /#login -->
