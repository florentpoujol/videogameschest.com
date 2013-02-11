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