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