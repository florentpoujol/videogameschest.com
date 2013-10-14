<?php

class Tag extends Eloquent
{
    //----------------------------------------------------------------------------------
    // CRUD METHODS


    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

    public function profiles()
    {
        return $this->belongsToMany('Profile');
    }
}
