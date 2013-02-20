<?php

class ExtendedEloquent extends Eloquent
{
    public static $timestamps = true;
    
    //----------------------------------------------------------------------------------

    public static function create($input)
    {
        return parent::create(clean_form_input($input));
    }

    public static function update($id, $input)
    {
        return parent::update($id, clean_form_input($input));
    }

    //----------------------------------------------------------------------------------
    // MAGIC METHODS

    /**
     * Handle the dynamic retrieval of attributes and associations.
     * 
     *
     * @param  string  $key
     * @return mixed
     */
    /*public function __get($key)
    {
        $value = parent::__get($key);

        if (is_string($value)) return $value;
        else return $value;
    }*/

    // NOTE : it's not a good idea to secure data agains XSS with e() or Security::xss_clean() here
    // because it will mess up with data that does not need to be escaped (especially json strings)

    // actually e() will mess up with json string but not Security::xss_clean()
    // I better make sure things I put in json strings are escaped instead


    //----------------------------------------------------------------------------------

    // for Former bundle
    public function __toString()
    {
        if ($this instanceof User) return $this->username;
        elseif ($this instanceof Developer || $this instanceof Game) return $this->name;
        else return static::$table;
    }
}