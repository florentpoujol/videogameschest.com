<?php

class Developer extends Profile
{
    public static $json_fields = array("stores", "devices", "operatingsystems", "technologies", 'socialnetworks', 'approved_by');

    public static $array_fields = array("stores", "devices", "operatingsystems", "technologies", );

    public static $names_urls_fields = array('socialnetworks');

    public static $secured_fields = array('name', 'email', 'pitch', 'logo', 'website', 'blogfeed', 'presskit','country');


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
	public static function create($input) 
	{
        $input = clean_form_input($input);

        // create user if email is set
        if (isset($input['email']) && trim($input['email']) != '') {
            $user = User::create(array(
                'username' => $input['name'],
                'email' => $input['email'],
                'do_not_display_success_msg' => '' // keep that
            ));

            $input['user_id'] = $user->id;
        }

        if ( ! isset($input['privacy'])) $input['privacy'] = 'submission';
        
        $input['approved_by'] = array();
        $input['pitch'] = e($input['pitch']);

        $dev = parent::create($input);
        
        HTML::set_success(lang('messages.adddev_success', array('name'=>$dev->name)));
        return $dev;
    }

    /**
     * Update a developer profile
     * @param  int $id     The dev's id
     * @param  array $input The dev's data
     * @return Developer   The updated dev instance
     */
    public static function update($id, $input)
    {
        $input = clean_form_input($input);

        $dev = parent::find($id);
        // checking name change
        if (isset($input['name']) && $dev->name != $input['name']) { // the user wan to change the dev name, must check is the name is not taken
            if (parent::where_name($input['name'])->first() != null) {
                HTML::set_error(
                    lang('messages.editdev_nametaken', array(
                        'name'=>$dev->name,
                        'id'=>$dev->id,
                        'newname'=>$input['name'])
                    )
                );

                return false;
            }
        }

        parent::update($id, $input); // returns an int ?
        $dev = Dev::find($id);

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
        if (in_array($key, static::$json_fields)) {
            $data = json_decode($this->get_attribute($key), true);
        }

       /* elseif (in_array($key, static::$secured_items)) {
            return Security::xss_clean(e($this->get_attribute($key)));
        }*/

        else $data = parent::__get($key);

        return $data; // I could also use the helper e() (html_entities())
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
