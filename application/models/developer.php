<?php

class Developer extends Profile
{
    // text fields which data is stored as json
    public static $json_fields = array('approved_by', "stores", "devices", "operatingsystems", "technologies", 'socialnetworks');

    // text fields which data is stored as json array
    public static $array_fields = array("stores", "devices", "operatingsystems", "technologies", );

    // text fields which data is stored as json object with a 'names' and 'urls' keys containing an array ot items
    public static $names_urls_fields = array('socialnetworks');

    // fields to secure against XSS before displaying
    public static $secured_fields = array('name', 'email', 'pitch', 'logo', 'website', 'email', 'blogfeed', 'presskit');

    // fields not to secure before displaying
    public static $safe_fields = array();



    //----------------------------------------------------------------------------------
    // CRUD METHODS

    /**
     * Update a developer profile
     * @param  int $id     The dev's id
     * @param  array $input The dev's data
     * @return Developer   The updated dev instance
     */
    public static function update($id, $input)
    {
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

        $input['socialnetworks'] = clean_names_urls_array($input['socialnetworks']);

        parent::update($id, $input);
        
        return true;
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

            return json_decode($attr, true);
        }

        return XssSecure(parent::__get($key));
    }


    //----------------------------------------------------------------------------------
    // RELATIONSHIPS
    // reports relationships is in Profile model

    public function games()
    {
        return $this->has_many('Game')/*->where_privacy('public')->or_where('privacy', '=', 'private')*/;
    }
}
