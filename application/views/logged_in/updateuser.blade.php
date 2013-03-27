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

    <ul class="nav nav-tabs" id="main-tabs">
        <li><a href="#profile-pane" data-toggle="tab">{{ lang('vgc.common.profile') }}</a></li>
        <li><a href="#blacklist-pane" data-toggle="tab">{{ lang('vgc.blacklist.title') }}</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane" id="profile-pane">
            <div class="row">
                <div class="span6">
                    <?php
                    $rules = array(
                        'username' => 'required|alpha_dash_extended|min:2',
                        'email' => 'required|min:5|email',
                    );
                    ?>
                    {{ Former::open_vertical(route('post_user_update'))->rules($rules) }}    
                        {{ Form::token() }}

                        @if (is_admin())
                            {{ Former::hidden('id', $user->id) }}
                        @endif

                        {{ lang('vgc.user.id') }} : {{ $user->id }} <br>
                        
                        <br>

                        {{ Former::text('username', lang('vgc.common.name')) }}

                        {{ Former::email('email', lang('vgc.common.email')) }}

                        {{-- Former::text('url_key', 'Url key')->help(lang('vgc.user.url_key_help')) --}}

                        @if (is_admin())
                            {{ Former::text('type', 'Account type')->help('"user," "developer" or "admin"') }}
                        @endif

                        {{ Former::primary_submit(lang('vgc.user.edit_title')) }} 
                    {{ Former::close() }} 
                </div>

                <div class="span6">
                    <?php
                    $rules = array(
                        'password' => 'min:5|confirmed',
                        'password_confirmation' => 'min:5|required_with:password',
                        'old_password' => 'min:5|required_with:password',
                    );

                    if (is_admin()) unset($rules['old_password']);
                    ?>
                    {{ Former::open_vertical(route('post_password_update'))->rules($rules) }}    
                        {{ Form::token() }}

                        @if (is_admin())
                            {{ Former::hidden('id', $user->id) }}
                        @endif

                        {{ Former::password('password') }}

                        {{ Former::password('password_confirmation', 'Password Confirmation') }}

                        {{ Former::password('old_password', 'Old password')->help(lang('vgc.user.old_password_help')) }}

                        {{ Former::primary_submit(lang('vgc.user.edit_password')) }}     
                    {{ Former::close() }}
                </div>
            </div>
        </div> <!-- /#profile-pane .tab-pane -->

        <div class="tab-pane" id="blacklist-pane">
            
            <p>
                {{ lang('vgc.blacklist.help') }}
            </p>

            <hr>
            
            <?php
            $rules = array('')
            ?>
            {{ Former::open_vertical(route('post_blacklist_update'))->rules($rules) }}    
                {{ Form::token() }}
                
                @if (is_admin())
                    {{ Former::hidden('user_id', $user->id) }}
                @endif

                <?php
                $profile_list = $user->blacklist['games'];
                $profile_type = "game";
                ?>
                @include('forms/profile_list')
            {{ Former::close() }}
        </div> <!-- /#blacklist-pane .tab-pane -->
    </div> <!-- /.tab-content -->
</div> <!-- /#edituser --> 

@section('jQuery')
// from edit user
$('#main-tabs a:first').tab('show');
$('#blacklist-tabs a:first').tab('show');
@endsection