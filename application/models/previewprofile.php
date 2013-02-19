<?php

class PreviewProfile extends ExtendedEloquent
{
    public static $table = 'preview_profiles';

    //----------------------------------------------------------------------------------
    // CRUD METHODS

    /**
     * Create a new profile
     * @param  object $profile The profile object
     * @return 
     */
    public static function create($profile) 
    {
        $input = array(
            'type' => $profile->type,
            'privacy' => 'publishing',
            $profile->type.'_id' => $profile->id,
            'data' => array(),
        );

        parent::create($input);
    }


    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

    // FIXME : do not work (return null) 15/02/2013
    // => fixed : again, dynamic method with relationhip do not work when the method name has not the model name
    // in this case, you have to manually set the foreign key
    public function public_profile()
    {
        $profile_type = $this->get_attribute('type');
        return $this->belongs_to(ucfirst($profile_type), $profile_type.'_id');
    }

    //----------------------------------------------------------------------------------
    // GETTERS SETTERS

    public function get_data()
    {
        return json_decode($this->get_attribute('data'), true);
    }

    public function set_data($data)
    {
        $this->set_attribute('data', json_encode($data));
    }
}

