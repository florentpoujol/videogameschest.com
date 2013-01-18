@section('page_title')
    {{ lang('login.title') }}
@endsection
<?php
$rules = array(
    'username' => 'required|min:5',
    'password' => 'required|min:5',
);
?>
<div id="login-form">
    <h2>{{ lang('login.title') }}</h2>

    <hr>

    {{ Former::open_inline(route('post_login'))->rules($rules) }}
        {{ Form::token() }}
        
        {{ Former::text('username', '')->placeholder(lang('login.name_label')) }}
        
        {{ Former::password('password', '')->placeholder(lang('login.password_label')) }}

        {{ Former::checkbox('keep_logged_in', '')->text(lang('login.keep_logged_in_label'))->check() }}
        
        {{ antiBot() }}

        {{ Former::primary_submit(lang('login.submit')) }}
    </form>

    <hr>

    <p>
        <a href="#collapse1" class="accordion-toggle" data-toggle="collapse">
            {{ lang('login.lost_password') }}
        </a>
    </p>

    <div id="collapse1" class="collapse">
        <?php
        $rules = array(
            'lost_password_username' => 'required|min:5',
        );
        ?>
        {{ Former::open_inline(route('post_lostpassword'))->rules($rules) }}
            {{ Form::token() }}

            {{ Former::text('lost_password_username', '')->placeholder(lang('login.name_label')) }} 

            {{ antiBot() }}

            {{ Former::info_submit(lang('login.lost_password')) }}
        </form>
    </div>
</div> 
<!-- /#admin_login -->

