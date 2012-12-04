<?php

class Developer extends Eloquent
{
	public static $timestamps = true;


	//----------------------------------------------------------------------------------

    /**
     * Create a new developer profile
     * @param  array $dev Data comming from the form
     * @return Developer       The Developer instance
     */
	public static function create($dev) 
	{
        unset($dev['csrf_token']);

        if (isset($dev['email']))
            $email = $dev['email'];
        unset($dev['email']);

        $create_user = true;
        if (isset($dev['user_id']))
        	$create_user = false;

        $form_items_as_json = array("technologies", "operatingsystems", "devices","stores", 'socialnetworks');

        foreach ($form_items_as_json as $item) {
            if (isset($dev[$item])) 
            {
                if ($item == 'socialnetworks') { // must sanitise the array, remove items with blank url
                    $dev[$item] = clean_names_urls_array($dev[$item]);
                }

                $dev[$item] = json_encode($dev[$item]);
            }
        }

        $dev['privacy'] = 'private';
        
        $dev = parent::create($dev);

        if ($create_user)
        {
	        $user = User::create(array(
	            'username' => $dev->name,
	            'email' => $email,
                'do_not_display_success_msg' => ''
	        ));

	        $dev->user_id = $user->id;
	        $dev->save();
    	}
    	
        
        HTML::set_success('The developer profile with name \''.$dev->name.'\' has successfully been created.');
        return $dev;
    }

    /**
     * Update a developer profile
     * @param  int $id         The developer id
     * @param  array $attributes The dev's data
     * @param  Developer $dev The dev instance
     * @return User The updateddev instance
     */
    public static function update($id, $attributes)
    {
        unset($attributes['csrf_token']);

        $dev = Dev::find($id);
        
        foreach ($attributes as $field => $attr) {
            $dev->$field = $attr;
        }

        $dev->save();

        HTML::set_success('The developer \"'.$dev->name.'\" (id : '.$dev->id.') has successfully been updated.');
        return $dev;
    }

	/**
	 * Created an array with data from the database, 
	 * with the specified $fields as key and value
	 * @param  string $key   The field used as array key
	 * @param  string $value The field used as array value
	 * @param  string $type  The type of profile
	 * @return array         The generated array
	 */
	public static function get_array($key, $value, $type = 'any')
	{
		if ($type == 'any') {
			$profiles = Profile::get(array($key, $value));
		}
		else {
			$profiles = Profile::where('type', '=', $type)->get(array($key, $value));
		}

		$array = array();

		foreach ($profiles as $profile) {
			$array[$profile->$key] = $profile->$value;
		}

		return $array;
	}


    public function get_technologies_array() 
    {
        return json_decode($this->get_attribute('technologies'), true);
    }


	//----------------------------------------------------------------------------------

	/**
     * Relationship method with the Users table
     * @return User The User instance, owner of this profile
     */
	public function user()
    {
        return $this->belongs_to('User');
    }
}