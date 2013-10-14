<?php

class ExtendedEloquent extends Eloquent
{
    
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
        if ($this instanceof Profile) return $this->name;
        else return static::$table;
    }
}