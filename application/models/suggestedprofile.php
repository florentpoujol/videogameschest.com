<?php

class SuggestedProfile extends ExtendedEloquent 
{
    //----------------------------------------------------------------------------------
    // CRUD methods

    public static function create($input)
    {
        $profile = parent::create($input);

        if ($profile->source == 'user') {
            HTML::set_success(lang('vgc.suggest.msg.create_success'));
        }

        Log::write('sugestedprofile create success', "New suggested profile with url='".$profile->url."' and source='".$profile->source."'.");
        return $profile;
    }
    
}   
