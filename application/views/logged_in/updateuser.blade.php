@section('page_title')
    {{ lang('user.edit_title') }}
@endsection

<?php
$rules = array(
    'username' => 'required|alpha_dash_extended|min:2',
    'email' => 'required|min:5|email',
);

$user = User::find($user_id);

Former::populate($user);

$old = Input::old();
if ( ! empty($old)) Former::populate($old);
?>
<div id="edituser">
    <h1>{{ lang('user.edit_title') }} <small>{{ $user->username }}</small></h1>

    <hr>

    <ul class="nav nav-tabs" id="main-tabs">
        <li><a href="#profile-pane" data-toggle="tab">{{ lang('common.profile') }}</a></li>
        <li><a href="#blacklist-pane" data-toggle="tab">{{ lang('blacklist.title') }}</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane" id="profile-pane">
            {{ Former::open_vertical(route('post_user_update'))->rules($rules) }}    
                {{ Form::token() }}

                @if (is_admin())
                    {{ Former::hidden('id', $user->id) }}
                @endif

                {{ lang('user.id') }} : {{ $user->id }} <br>
                
                <br>

                {{ Former::text('username', lang('common.name')) }}

                {{ Former::email('email', lang('common.email')) }}

                {{-- Former::text('url_key', 'Url key')->help(lang('user.url_key_help')) --}}

                @if (is_admin())
                    {{ Former::text('type', 'Account type')->help('"user," "developer" or "admin"') }}
                @endif

                {{ Former::primary_submit(lang('user.edit_title')) }} 
            {{ Former::close() }} 

            <hr>

            <?php
            $rules = array(
                'password' => 'min:5|confirmed',
                'password_confirmation' => 'min:5|required_with:password',
                'oldpassword' => 'min:5|required_with:password',
            );

            if (is_admin()) unset($rules['oldpassword']);
            ?>
            {{ Former::open_vertical(route('post_password_update'))->rules($rules) }}    
                {{ Form::token() }}

                @if (is_admin())
                    {{ Former::hidden('id', $user->id) }}
                @endif

                {{ Former::password('password') }}

                {{ Former::password('password_confirmation', 'Password Confirmation') }}

                {{ Former::password('old_password', 'Old password')->help(lang('user.old_password_help')) }}

                {{ Former::primary_submit(lang('user.edit_password')) }}     
            {{ Former::close() }}
        </div> <!-- /#profile-pane .tab-pane -->

        <div class="tab-pane" id="blacklist-pane">
            
            <ul class="nav nav-tabs" id="blacklist-tabs">
                @foreach (Config::get('vgc.profile_types') as $profile_type)
                    <li><a href="#{{ $profile_type }}-pane" data-toggle="tab">{{ lang('common.'.$profile_type) }}</a></li>
                @endforeach
            </ul>

            <div class="tab-content">
                <?php
                $rules = array('')
                ?>
                @foreach (Config::get('vgc.profile_types') as $profile_type)
                    <div class="tab-pane" id="{{ $profile_type }}-pane">
                        {{ Former::open_vertical(route('post_blacklist_update'))->rules($rules) }}    
                            {{ Form::token() }}
                            
                            @if (is_admin())
                                {{ Former::hidden('id', $user->id) }}
                            @endif

                            <?php
                            $profile_list = $user->blacklist[$profile_type.'s'];
                            ?>
                            @include('forms/profile_list')
                        {{ Former::close() }}
                    </div> <!-- /#{{ $profile_type }}-pane .tab-pane -->
                @endforeach
            </div> <!-- /.tab-content -->
        </div> <!-- /#blacklist-pane .tab-pane -->
    </div> <!-- /.tab-content -->
</div> <!-- /#edituser --> 

@section('jQuery')
// from edit user
$('#main-tabs a:first').tab('show');
$('#blacklist-tabs a:first').tab('show');
@endsection