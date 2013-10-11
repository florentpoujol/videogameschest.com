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

    // for Former bundle
    public function __toString()
    {
        if ($this instanceof User) return $this->username;
        elseif ($this instanceof Developer || $this instanceof Game) return $this->name;
        else return static::$table;
    }
}