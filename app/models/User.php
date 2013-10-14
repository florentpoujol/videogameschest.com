<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}


    //----------------------------------------------------------------------------------
    // Modif for VGC below
	
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = array();


    //----------------------------------------------------------------------------------
    // CRUD METHODS

    public static function create(array $attributes = array()) {
        if (isset($input["password"]) && trim($input["password"]) != "") {
            $input["password"] = Hash::make($input["password"]);
        }        
        
        $input["url_key"] = Str::random(40);

        if ( ! isset($input['type'])) $input['type'] = 'admin';

        $user = parent::create( clean_form_input( $input ) );

        // Log
        HTML::set_success(lang('register.msg.register_success', array('username'=>$user->username)));
        Log::info('user create success New user created (id='.$user->id.') (username='.$user->username.') (email='.$user->email.')');

        return $user;
    }

    public function update(array $input = array()) {
        $user = $this;

        // checking name change
        if (isset($input['username']) && $user->username != $input['username']) { // the user want to change the dev name, must check is the name is not taken
            if (parent::whereUsername($input['username'])->first() != null) {
                HTML::set_error(
                    lang('user.msg.edituser_nametaken', array(
                        'username' => $user->username,
                        'id' => $user->id,
                        'newname' => $input['username'])
                    )
                );

                return false;
            }
        }

        if (isset($input['password']) && trim($input['password']) != '') $input['password'] = Hash::make($input['password']);
        else unset($input['password']);

        $update_success = parent::update( clean_form_input($input) );
        if (! $update_success) {
            $log_msg = "The user '".$user->username."' (id : '".$user->id."') was not updated because of an error.";
            if ($user->id != user_id()) $html_msg = $log_msg;
            else $html_msg = lang('user.msg.update_error');
            HTML::set_error($html_msg);
            Log::error('user update error ' .  $log_msg);

            return false;
        }

        $log_msg = "The user '".$user->username."' (id : '".$user->id."') has successfully been updated.";
        if ($user->id != user_id()) $html_msg = $log_msg;
        else $html_msg = lang('user.msg.update_success');
        HTML::set_success($html_msg);
        Log::info('user update success ' . $log_msg);

        return true;
    }


    //----------------------------------------------------------------------------------

    /**
     * When the user as lost its password
     * @param integer $step
     */
    public function setNewPassword($step = 2) 
    {
        // step 1 : send conf email to user
        if ($step == 1) {
            // message
            HTML::set_success(lang('lostpassword.msg.confirmation_email_sent'));
            // Log::write('user lostpassword info', 'User "'.$this->username.'" asked for a new password. (id='.$this->id.') (email='.$this->email.') (url_key='.$this->url_key.')');

            // email
            $link = URL::to_route('get_lostpassword_confirmation', array($this->id, $this->url_key));
            $subject = lang('emails.lostpassword_confirmation.subject');
            $html = lang('emails.lostpassword_confirmation.html', array(
                'username' => $this->username,
                'link' => $link
            ));

            send_mail($this->email, $subject, $html);
        } 
        
        // setp 2 : generate new password then send by mail
        else {
            $password = Str::random(20);

            $this->password = Hash::make($password);
            $this->save();

            // message
            HTML::set_success(lang('lostpassword.msg.new_password_success'));
            Log::info('user lostpassword success | A new password for user "'.$this->username.'" (id='.$this->id.') as successfully been generated.');

            // email
            $subject = lang('emails.lostpassword_success.subject');
            $html = lang('emails.lostpassword_success.html', array(
                'username' => $this->username,
                'password' => $password,
                'login_link' => URL::to_route('get_login_page'),
            ));

            send_mail($this->email, $subject, $html);
        }
    }


    //----------------------------------------------------------------------------------
    // GETTERS

    // for Former bundle
    public function __toString()
    {
        return $this->username;
    }

}
