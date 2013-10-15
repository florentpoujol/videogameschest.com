<?php

class Suggestion extends Eloquent
{

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = array();


    //----------------------------------------------------------------------------------
    // CRUD METHODS

    public static function create(array $input = array()) 
    {
        $input = clean_form_input($input);

        if ($input['source'] == 'user') {
            HTML::set_success(lang('suggestion.msg.create_success'));
        }
        $suggestion = parent::create($input);
        
        return $suggestion;
    }

    public function delete() {
        $delete = parent::delete();

        if ($delete) {
            HTML::set_success(lang('suggestion.msg.delete_success', array(
                'id' => $this->id,
                'url' => $this->url
            )));
        }
        else {
            HTML::set_error(lang('suggestion.msg.delete_error', array(
                'id' => $this->id,
                'url' => $this->url
            )));
        }
    }

}
