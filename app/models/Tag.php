<?php

class Tag extends ExtendedEloquent
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
