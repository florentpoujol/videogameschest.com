<?php

class Developer extends Profile
{
    public static $json_items = array("stores", "devices", "operatingsystems", "technologies", 'socialnetworks', 'approved_by');

    public static $array_items = array("stores", "devices", "operatingsystems", "technologies", );

    public static $names_urls_items = array('socialnetworks');

    public static $secured_items = array('name', 'email', 'pitch', 'logo', 'website', 'blogfeed', 'country');


    //----------------------------------------------------------------------------------
    // CONSTRUCTOR

    public function __construct($attributes = array(), $exists = false)
    {
        parent::__construct($attributes, $exists);
    }


    //----------------------------------------------------------------------------------
    // CRUD METHODS

    /**
     * Create a new developer profile
     * @param  array $dev Data comming from the form
     * @return Developer       The Developer instance
     */
	public static function create($form) 
	{
        $form = clean_form_input($form);

        // create user if email is set
        if (isset($form['email']) && trim($form['email']) != '') {
            $user = User::create(array(
                'username' => $form['name'],
                'email' => $form['email'],
                'do_not_display_success_msg' => '' // keep that
            ));

            $form['user_id'] = $user->id;
        }

        if ( ! isset($form['privacy'])) $form['privacy'] = 'submission';
        
        $form['approved_by'] = array();

        $dev = parent::create($form);
        
        HTML::set_success(lang('messages.adddev_success', array('name'=>$dev->name)));
        return $dev;
    }

    /**
     * Update a developer profile
     * @param  int $id     The dev's id
     * @param  array $form The dev's data
     * @return Developer   The updated dev instance
     */
    public static function update($id, $form)
    {
        $form = clean_form_input($form);

        // checking name change
        if (isset($form['name'])) {
            $dev = parent::find($id);

            if ($dev->name != $form['name']) { // the user wan to change the dev name, must check is the name is not taken
                if (parent::where('name', '=', $form['name'])->first() != null) {
                    HTML::set_error(
                        lang('messages.editdev_nametaken', array(
                            'name'=>$dev->name,
                            'id'=>$dev->id,
                            'newname'=>$form['name'])
                        )
                    );

                    return false;
                }
            }
        }

        parent::update($id, $form); // returns an int ?

        HTML::set_success(lang('messages.editdev_success'
            ,array('name'=>$dev->name, 'id'=>$dev->id)
        ));

        return true;
    }


    //----------------------------------------------------------------------------------
    // REVIEWS

    /**
     * Do stuffs when the profile passed a review
     * @param  string $review  Review type
     * @param  string $profile The profile type
     */
    public function passed_review($user = null)
    {
        parent::passed_review($this->user);
    }

    /**
     * Do stuffs when the profile failed a review
     * @param  string $review   Review type
     * @param  string $profile The profile type
     */
    public function failed_review($review, $profile = 'developer', $user = null)
    {
        parent::failed_review($review, $profile, $this->user);
    }



	//----------------------------------------------------------------------------------
    // GETTERS

    public function parsed_pitch() 
    {
        return nl2br(parse_bbcode($this->get_attribute('pitch')));
    }


    //----------------------------------------------------------------------------------
    // MAGIC METHODS

    /**
     * Handle the dynamic setting of attributes.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function __set($key, $value)
    {
        if (in_array($key, static::$json_items)) {
            if (in_array($key, static::$names_urls_items)) {
                $value = clean_names_urls_array($value);
            }

            $this->set_attribute($key, json_encode($value));
        } else parent::__set($key, $value);
    }

    /**
     * Handle the dynamic retrieval of attributes and associations.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if (in_array($key, static::$json_items)) return json_decode($this->get_attribute($key), true);

        elseif (in_array($key, static::$secured_items)) {
            return Security::xss_clean(e($this->get_attribute($key)));
        }

        else return parent::__get($key);
    }


    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

	public function user()
    {
        return $this->belongs_to('User');
    }

    public function games()
    {
        return $this->has_many('Game');
    }
}
