<?php
$rules = array(
    'username' => 'required|min:5',
    'email' => 'required|min:5|email',
    'password' => 'required|min:5|confirmed',
    'password_confirmation' => 'required|min:5|required_with:password',
);
?>
{{ Former::open_vertical(route('post_register'))->rules($rules) }}
    {{ Form::token() }}
    
    {{ Former::text('username', '')->placeholder(lang('register.username')) }}

    {{ Former::text('email', '')->placeholder(lang('common.email'))->help(lang('register.email_help')) }}
    
    {{ Former::password('password', '')->placeholder(lang('register.password')) }}

    {{ Former::password('password_confirmation', '')->placeholder(lang('register.password_confirmation')) }}
    
    {{ antiBot() }}

    <br>
    
    {{ Former::primary_submit(lang('register.submit')) }}
{{ Former::close() }}
