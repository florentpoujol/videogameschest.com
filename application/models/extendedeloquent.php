<?php

class ExtendedEloquent extends Eloquent
{
    public static $timestamps = true;
    
    //----------------------------------------------------------------------------------

    // for Former bundle
    public function __toString()
    {
        if ($this instanceof User) return $this->username;
        else return $this->name;
    }
}