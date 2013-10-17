<?php

class Suggestion extends Eloquent
{

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = array();

    public function profile()
    {
        return $this->belongsTo('Profile');
    }

    //----------------------------------------------------------------------------------
    // CRUD METHODS

    public static function create(array $input = array()) 
    {
        $input = clean_form_input($input);

        
        $suggestion = parent::create($input);

        if ($input['source'] == 'user') {
            HTML::set_success(lang('suggestion.msg.create_success'));
        }

        $msg = "Suggestion width id '".$suggestion->id."', url '".$suggestion->url."', title '".$suggestion->title."' and source '".$suggestion->source."' has been created.";
        if (is_admin()) {
            HTML::set_success( $msg );
        }
        Log::info( "suggestion create success : ".$msg );
        
        return $suggestion;
    }

    public function update(array $input = array())
    {
        $input = array_intersect_key( $input, $this->attributes );
        $update = parent::update( $input );

        $updated_fields = get_updated_fields_string( $input );
        
        if ($update) {
            $msg = "Suggestion with id '" . $this->id . "' has been updated with ".$updated_fields;
            HTML::set_success( $msg );
            Log::info( 'success update suggestion : ' . $msg );
        } else {
            $msg = "Suggestion with id '" . $this->id . "' has not been updated with ".$updated_fields;
            HTML::set_error( $msg );
            Log::error( 'error update suggestion : ' . $msg );
        }
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
