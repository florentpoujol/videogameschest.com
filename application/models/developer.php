<?php

class Developer extends Profile
{
    // text fields which data is stored as json
    public static $json_fields = array("stores", "devices", "operatingsystems", "technologies", 'socialnetworks', 'approved_by');

    // text fields which data is stored as json array
    public static $array_fields = array("stores", "devices", "operatingsystems", "technologies", );

    // text fields which data is stored as json object with a 'names' and 'urls' keys containing an array ot items
    public static $names_urls_fields = array('socialnetworks');

    // fields to secure against XSS before displaying
    public static $secured_fields = array('name', 'email', 'pitch', 'logo', 'website', 'email', 'blogfeed', 'presskit');


    //----------------------------------------------------------------------------------
    // CONSTRUCTOR

    /*public function __construct($attributes = array(), $exists = false)
    {
        parent::__construct($attributes, $exists);
    }*/


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

        if ( ! isset($input['privacy'])) $input['privacy'] = 'private';
        
        $input['approved_by'] = array();
        $input['pitch'] = $input['pitch'];

        $dev = parent::create($input);
        
        $msg = lang('developer.msg.adddev_success', array(
            'name'=>$dev->name,
            'id' => $dev->id
        ));
        HTML::set_success($msg);
        Log::write('developer create success', $msg);

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
        if (isset($input['name']) && $dev->name != $input['name']) { // the user want to change the dev name, must check is the name is not taken
            if (parent::where_name($input['name'])->first() != null) {
                HTML::set_error(
                    lang('developer.msg.editdev_nametaken', array(
                        'name' => $dev->name,
                        'id' => $dev->id,
                        'newname' => $input['name'])
                    )
                );

                return false;
            }
        }

        parent::update($id, $input); // returns an int ?
        $dev = Dev::find($id);

        $msg = lang('developer.msg.editdev_success', array(
            'name' => $dev->name,
            'id' => $dev->id
        ));
        HTML::set_success($msg);
        Log::write('developer update success', $msg);

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
        if (in_array($key, static::$json_fields)) {
            if (in_array($key, static::$names_urls_fields)) {
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
            $attr = $this->get_attribute($key);

            if (in_array($key, static::$array_fields)) {
                // make sure $attr is a json array and not an empty string
                if (trim($attr) == '') $attr = '[]';
            }

            $data = json_decode($attr, true);
        }

        elseif (in_array($key, static::$secured_fields)) {
            return xssSecure($this->get_attribute($key));
        }

        else $data = parent::__get($key);

        return $data; // I could also use the helper e() (html_entities())
    }


    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

    public function games()
    {
        return $this->has_many('Game');
    }
}
