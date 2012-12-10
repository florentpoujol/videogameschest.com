<?php

class Developer extends Eloquent
{
	public static $timestamps = true;

    private static $json_items = array("technologies", "operatingsystems", "devices", "stores", 'socialnetworks');

    public static $array_items = array("technologies", "operatingsystems", "devices", "stores");


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

        $dev = Dev::find($id);

        if ($dev->name != $form['name']) { // the user wan to change the dev name, must check is the name is not taken
            if (Dev::where('name', '=', $form['name'])->first() != null) {
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

        HTML::set_success(lang('messages.editdev_success', 
            array('name'=>$dev->name, 'id'=>$dev->id))
        );
        return true;
    }


    //----------------------------------------------------------------------------------
    // REVIEWS

    /**
     * Do stuffs when the profile passed the submission review
     */
    public function submission_review_success()
    {
        $this->privacy = 'private';
        $this->approved_by = '';
        $this->review_start_date = '0000-00-00 00:00:00';
        $this->save();

        // @TODO send mail with text emails.developer_submission_review_success
    }

    /**
     * Do stuffs when the profile failed at the submission review
     */
    public static function submission_review_fail($dev)
    {
        User::delete($dev->user->id);
        Dev::delete($dev->id);
    }

    /**
     * Do stuffs when the profile passed the publishing review
     */
    public function publishing_review_success()
    {
        $this->privacy = 'public';
        $this->approved_by = '';
        $this->review_start_date = '0000-00-00 00:00:00';
        $this->save();

        // @TODO send mail with text emails.developer_publishing_review_success
    }

    /**
     * Do stuffs when the profile failed at the publishing review
     */
    public static function publishing_review_fail($dev)
    {
        $this->privacy = 'private';
        $this->approved_by = '';
        $this->review_start_date = '0000-00-00 00:00:00';
        $this->save();

        // @TODO send mail to dev with text emails.developer_publishing_review_success
    }


	//----------------------------------------------------------------------------------
    // GETTERS

    public function json_to_array($attr)
    {
        return json_decode($this->get_attribute($attr), true);
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


    //----------------------------------------------------------------------------------
    
    // for Former bundle
    public function __toString()
    {
        return $this->name;
    }
}
