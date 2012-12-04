<?php

class Developer extends Eloquent
{
	public static $timestamps = true;
    private static $json_items = array("technologies", "operatingsystems", "devices", "stores", 'socialnetworks');


	//----------------------------------------------------------------------------------

    /**
     * Create a new developer profile
     * @param  array $dev Data comming from the form
     * @return Developer       The Developer instance
     */
	public static function create($dev) 
	{
        unset($dev['csrf_token']);

        if (isset($dev['email'])) {
            $email = $dev['email'];
        }
        unset($dev['email']);

        $create_user = true;
        if (isset($dev['user_id'])) {
        	$create_user = false;
        }

        foreach (static::$json_items as $item) {
            if (isset($dev[$item])) {
                if ($item == 'socialnetworks') { // must sanitise the array, remove items with blank url
                    $dev[$item] = clean_names_urls_array($dev[$item]);
                }

                $dev[$item] = json_encode($dev[$item]);
            }
        }

        $dev['privacy'] = 'private';
        
        $dev = parent::create($dev);

        if ($create_user) {
	        $user = User::create(array(
	            'username' => $dev->name,
	            'email' => $email,
                'do_not_display_success_msg' => ''
	        ));

	        $dev->user_id = $user->id;
	        $dev->save();
    	}
        
        HTML::set_success('The developer profile with name \"'.$dev->name.'\" has successfully been created.');
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

        $dev = Dev::find($id);

        if ($dev->name != $form['name']) { // the user wan to change the dev name, must check is the name is not taken
            if (Dev::where('name', '=', $form['name']) != null) {
                HTML::set_error(
                    __('vgc.msg_editdev_nametaken', array(
                        'devname'=>$dev->name,
                        'devid'=>$dev->id,
                        'newname'=>$form['name'])
                    )
                );

                return false;
            }
        }
        
        foreach ($form as $field => $attr) {
            if (in_array($field, static::$json_items)) {
                if ($field == 'socialnetworks') { // must sanitise the array, remove items with blank url
                    $attr = clean_names_urls_array($attr);
                }

                $attr = json_encode($attr);
            }
            
            $dev->$field = $attr;
        }

        $dev->save();

        HTML::set_success('The developer profile with name \"'.$dev->name.'\" (id : '.$dev->id.') has successfully been updated.');
        return true;
    }


	/**
	 * Created an array with data from the database, 
	 * with the specified $fields as key and value
	 * @param  string $key   The field used as array key
	 * @param  string $value The field used as array value
	 * @param  string $type  The type of profile
	 * @return array         The generated array
	 */
	/*public static function get_array($key, $value, $type = 'any')
	{
		if ($type == 'any') {
			$profiles = Profile::get(array($key, $value));
		} else {
			$profiles = Profile::where('type', '=', $type)->get(array($key, $value));
		}

		$array = array();

		foreach ($profiles as $profile) {
			$array[$profile->$key] = $profile->$value;
		}

		return $array;
	}*/


	/**
     * Relationship method with the Users table
     * @return User The User instance, owner of this profile
     */
	public function user()
    {
        return $this->belongs_to('User');
    }
}