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
            'data' => $profile->to_array()
        );

        parent::create($input);
    }


    /**
     * Update a developer profile
     * @param  int $id     The real profile id
     * @param  array $input The profile data
     * @return Developer   The updated dev instance
     */
    public static function update($profile, $input)
    {
        dd($input);
        $profile->preview_profile->datajson = $input; 
    }




    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

    // FIXME : do not work (return null) 15/02/2013
    // => fixed : again, dynamic method with relationhip do not work whrn the method name has not the model name
    // in this case, you have to manually set the foreign key
    public function profile()
    {
        $profile_type = $this->get_attribute('type');
        return $this->belongs_to(ucfirst($profile_type), $profile_type.'_id');
    }

    //----------------------------------------------------------------------------------
    // GETTERS SETTERS

    public function get_data()
    {
        
        return unserialize($this->get_attribute('data'));
    }

    public function set_data($data)
    {
        $this->set_attribute('data', serialize($data));
    }

    public function get_datajson()
    {
        return json_decode($this->get_attribute('datajson'), true);
    }

    public function set_datajson($data)
    {
        $this->set_attribute('datajson', json_encode($data));
    }

    //----------------------------------------------------------------------------------
    // GETTERS

    public function get_parsed_pitch() 
    {
        $data = $this->get_datajson();

        return nl2br(parse_bbcode(xssSecure($data['pitch'])));
    }


    //----------------------------------------------------------------------------------
    // MAGIC METHODS

    /**
     * Handle the dynamic retrieval of attributes and associations.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($field)
    {
        $profile_type = $this->get_attribute('type');
        $data = $this->get_datajson();

        $value = $this->get_attribute($field);

        if ($value === null && isset($data[$field])) {
            return XssSecure($data[$field]);
        }

        return XssSecure(parent::__get($field));
    }
}

