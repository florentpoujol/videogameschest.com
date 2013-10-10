<?php

class Game extends Profile
{
    

    
    //----------------------------------------------------------------------------------
    // CRUD METHODS

    public static function update($id, $input)
    {
        // checking name change
        $game = parent::find($id);
        if (isset($input['name']) && $game->name != $input['name']) {  // the user want to change the name, must check if the name is not taken
            if (parent::where_name($input['name'])->first() != null) {
                HTML::set_error(
                    lang('profile.msg.update_nametaken', array(
                                                            'profile_type' => 'game',
                                                            'name' => $game->name,
                                                            'id' => $game->id,
                                                            'newname' => $input['name']
                                                        )
                    )
                );

                return false;
            }
        }

        foreach (static::$names_urls_fields as $field) {
            $input[$field] = clean_names_urls_array($input[$field]);
        }
        
        $game = parent::update($id, $input);
        return true;
    }


    //----------------------------------------------------------------------------------
    // GETTERS

    public function set_price($price)
    {
        $this->set_attribute('price', str_replace(",", ".", $price));
    }


}
