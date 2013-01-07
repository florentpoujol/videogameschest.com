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
        return Security::xss_clean(parent::__get($key)); // could use the helper e()
    }


    //----------------------------------------------------------------------------------

    // for Former bundle
    public function __toString()
    {
        if ($this instanceof User) return $this->username;
        else return $this->name;
    }
}