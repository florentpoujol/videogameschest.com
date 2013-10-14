@section('page_title')
    {{ lang('vgc.user.edit_title') }}
@endsection

<?php
$user = User::find($user_id);

Former::populate($user);

$old = Input::old();
if ( ! empty($old)) Former::populate($old);
?>
<div id="edituser">
    <h1>{{ lang('vgc.user.edit_title') }} <small>{{ $user->username }}</small></h1>

    <hr>

    <div class="row">
        <div class="span6">
            <?php
            $rules = array(
                'username' => 'required|alpha_dash|min:2',
                'email' => 'required|min:5|email',
            );
            ?>
            {{ Former::open_vertical(route('post_user_update'))->rules($rules) }}    
                {{ Form::token() }}

                {{ Former::hidden('id', $user->id) }}

                {{ lang('vgc.user.id') }} : {{ $user->id }} <br>
                
                <br>

                {{ Former::text('username', lang('vgc.common.name')) }}

                {{ Former::email('email', lang('vgc.common.email')) }}

                {{ Former::primary_submit(lang('vgc.user.edit_title')) }} 
            {{ Former::close() }} 
        </div>

        <div class="span6">
            <?php
            $rules = array(
                'password' => 'min:5|confirmed',
                'password_confirmation' => 'min:5|required_with:password',
                'old_password' => 'min:5',
            );
            ?>
            {{ Former::open_vertical(route('post_password_update'))->rules($rules) }}    
                {{ Form::token() }}

                {{ Former::hidden('id', $user->id) }}
                
                {{ Former::password('password')->value(" ") }} <!-- value() is set because it seems that the field would be pre filled with the password ? -->

                {{ Former::password('password_confirmation', 'Password Confirmation') }}

                {{ Former::password('old_password', 'Old password') }}

                {{ Former::primary_submit(lang('vgc.user.edit_password')) }}     
            {{ Former::close() }}
        </div>
    </div>
</div> <!-- /#edituser --> 

@section('jQuery')

@endsection