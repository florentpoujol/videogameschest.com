<?php

class ExtendedEloquent extends Eloquent
{
    public static $timestamps = true;
    
    //----------------------------------------------------------------------------------
    // MAGIC METHODS

    /**
     * Handle the dynamic retrieval of attributes and associations.
     * Secure all data against XSS
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        $value = parent::__get($key);

        if (is_string($value)) return Security::xss_clean($value); // could also use Security::xss_clean() to allow safe html
        else return $value; 
    }


    //----------------------------------------------------------------------------------

    // for Former bundle
    public function __toString()
    {
        if ($this instanceof User) return $this->username;
        elseif ($this instanceof Developer || $this instanceof Game) return $this->name;
        else return static::$table;
    }
}