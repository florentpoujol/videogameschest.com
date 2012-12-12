<?php

class Developer extends ExtendedEloquent
{
    public static $json_items = array("technologies", "operatingsystems", "devices", "stores", 'socialnetworks', 'approved_by');

    public static $array_items = array("technologies", "operatingsystems", "devices", "stores");

    public static $names_urls_items = array('socialnetworks');


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
        unset($form['email']);

        if ( ! isset($form['privacy'])) $form['privacy'] = 'private';
        elseif ($form['privacy'] == 'submission') $form['review_start_date'] = date_create();
        
        var_dump($form);
        $dev = parent::create($form);
        var_dump($dev);
        HTML::set_success(lang('messages.adddev_success',array('name'=>$dev->name)));
        return $dev;
    }

    /**
     * Update a developer profile
     * @param  int $id         The developer id
     * @param  array $attributes The dev's data
     * @param  Developer $dev The dev instance
     * @return User The updateddev instance
     */
    public static function update($id, $form)
    {
        unset($form['csrf_token']);

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
        
        /*foreach ($form as $field => $attr) {
            if (in_array($field, static::$json_items)) {
                if ($field == 'socialnetworks') { // must sanitise the array, remove items with blank url
                    $attr = clean_names_urls_array($attr);
                }

                $attr = json_encode($attr);
            }
            
            $dev->$field = $attr;
        }

        $dev->save();*/

        foreach ($form as $field => $attr) {
            if (in_array($field, static::$json_items)) {
                if ($field == 'socialnetworks') { // must sanitise the array, remove items with blank url
                    $attr = clean_names_urls_array($attr);
                }

                $form[$field] = json_encode($attr);
            }
        }

        $dev = parent::update($id, $form);

        HTML::set_success(lang('messages.editdev_success', 
            array('name'=>$dev->name, 'id'=>$dev->id))
        );
        return true;
    }


    //----------------------------------------------------------------------------------
    // REVIEWS

    /**
     * Do stuffs when the profile passed a review
     * @param  string $review  Review type
     * @param  string $profile The profile type
     */
    public function passed_review($review, $profile = 'developer')
    {
        parent::passed_review($review, $profile);
    }

    /**
     * Do stuffs when the profile failed a review
     * @param  string $review   Review type
     * @param  string $profile The profile type
     */
    public function failed_review($review, $profile = 'developer')
    {
        parent::failed_review($review, $profile);
    }



	//----------------------------------------------------------------------------------
    // GETTERS


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
        else return parent::__get($key);
    }


    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

	public function user()
    {
        return $this->belongs_to('User');
    }

    public function developer()
    {
        return $this;
    }

    public function dev()
    {
        return $this->developer();
    }

    public function games()
    {
        return $this->has_many('Game');
    }
}
