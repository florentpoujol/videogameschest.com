@section('page_title')
    {{ lang('user.edit_title') }}
@endsection

<?php
$rules = array(
    'username' => 'required|min:5',
    'email' => 'required|min:5|email',
    'password' => 'min:5|confirmed',
    'password_confirmation' => 'min:5|required_with:password',
    'oldpassword' => 'min:5|required_with:password',
    'type' => 'required|in:dev,admin'
);

$user = User::find($user_id);

$a_user = $user->to_array();
if ($a_user['crosspromotion_subscription'] == 0) unset($a_user['crosspromotion_subscription']);
Former::populate($a_user);

$old = Input::old();
if ( ! empty($old)) {
    Former::populate($old);
}
?>
<div id="edituser">
    <h1>{{ lang('user.edit_title') }}</h1>

    <hr>

    {{ Former::open_vertical(route('post_edituser'))->rules($rules) }}    
        {{ Form::token() }}

        @if (is_admin())
            {{ Former::hidden('id', $user->id) }}
        @endif

        {{ lang('user.id') }} : {{ $user->id }} <br>
        
        <br>

        {{ Former::text('username', lang('common.name')) }}

        {{ Former::email('email', lang('common.email')) }}

        {{ Former::xlarge_text('secret_key', 'Secret key')->help(lang('user.secret_key_help')) }}

        @if (is_admin())
            {{ Former::text('type', 'Account type')->help('"dev" or "admin"') }}
        @endif

        {{ Former::password('password') }}

        {{ Former::password('password_confirmation', 'Password Confirmation') }}

        {{ Former::password('old_password', 'Old password')->help(lang('user.old_password_help')) }}

        {{ Former::primary_submit(lang('user.edit_title')) }}


        @if ( ! is_admin())
            <!-- crosspromotion -->
            <hr>

            <h3>{{ lang('crosspromotion.title') }}</h3>

            @if (is_admin())
                {{ Former::checkbox('crosspromotion_subscription', '')->text('Cross promotion') }}
            @elseif (false)
                @if (user()->crosspromotion_subscription == 1)
                    <p>
                        {{ lang('crosspromotion.edit_user_subscription_text') }}
                    </p>

                    <p>[Button to subscribe]</p>
                @else
                    <p>
                        {{ lang('crosspromotion.edit_user_unsubscription_text') }}
                    </p>

                    <p>[Button to UNsubscribe]</p>
                @endif
            @else
                <p>
                    The cross-promotion service does not yet require a subscription to be active. <br>
                    You can select the games you want to cross-promote from your game's profiles.
                </p>
            @endif

            <!-- /crosspromotion -->
        @endif

        <hr>

        
    </form>
</div> <!-- /#edituser --> 
