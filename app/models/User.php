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
	
    //----------------------------------------------------------------------------------
    // CONSTRUCTOR

    public function __construct($attributes = array(), $exists = false)
    {
        parent::__construct($attributes, $exists);
    }


    //----------------------------------------------------------------------------------
    // CRUD METHODS

    /**
     * Create a new user
     * @param  array $input data comming from the form
     * @return User       The user instance
     */ 
    public function createFromInput($input) 
    {
        if (isset($input["password"]) && trim($input["password"]) != "") {
            $input["password"] = Hash::make($input["password"]);
        }        
        
        $input["url_key"] = Str::random(40);

        if ( ! isset($input['type'])) $input['type'] = 'admin';

        // parent::update($this->id, clean_form_input($input));

        foreach( clean_form_input($input) as $key => $value ) {
            $user->$key = $value;
        }
        $user->save();

        // Log
        HTML::set_success(lang('register.msg.register_success', array('username'=>$this->username)));
        // Log::write('user create success', 'New user created (id='.$user->id.') (username='.$user->username.') (email='.$user->email.')');

        return $this;
    }

    /**
     * Update a user
     * @param  int $id         The user id
     * @param  array $input The user's data
     * @return User The user instance
     */
    public function updateFromInput($input)
    {
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

        foreach( clean_form_input($input) as $key => $value ) {
            $user->$key = $value;
        }
        $user->save();

        

        $log_msg = "The user '".$user->username."' (id : '".$user->id."') has successfully been updated.";
        if ($user->id != user_id()) $html_msg = $log_msg;
        else $html_msg = lang('user.msg.update_success');
        HTML::set_success($html_msg);
        // Log::write('user update success', $log_msg);

        return $user;
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
            // Log::write('user lostpassword success', 'A new password for user "'.$this->username.'" (id='.$this->id.') as successfully been generated.');

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

    /*public function getName()
    {
        return $this->get_attribute('username');
    }*/

    // for Former bundle
    public function __toString()
    {
        return $this->username;
    }

}
