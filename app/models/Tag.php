<?php

class Tag extends Eloquent
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
        $tag = parent::create( array( 'name' => $input['name'] ) );

        $msg = "Tag with id '".$tag->id."' and name '".$tag->name."' has been created";
        HTML::set_success( $msg );
        Log::info( "tag create success : $msg" );
    }

    public function update(array $input = array()) 
    {
        $input = array_intersect_key( $input, $this->attributes );
        $update = parent::update( $input );
        $updated_fields = get_updated_fields_string( $input );

        if ($update) {
            $msg = "Tag with id '".$this->id."' has been updated with $updated_fields";
            HTML::set_success( $msg );
            Log::info( "tag update success : $msg" );
        }
        else {
            $msg = "Tag with id '".$this->id."' and name '".$this->name."' has not been updated.";
            HTML::set_error( $msg );
            Log::error( "tag update error : $msg" );
        }
    }

    public function delete()
    {
        $delete = parent::delete();

        if ($delete) {
            $msg = "Tag with id '".$this->id."' and name '".$this->name."' has been deleted";
            HTML::set_success( $msg );
            Log::info( "tag delete success : $msg" );
        }
        else {
            $msg = "Tag with id '".$this->id."' and name '".$this->name."' has not been deleted";
            HTML::set_error( $msg );
            Log::error( "tag delete error : $msg" );
        }
    }


    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

    public function profiles()
    {
        return $this->belongsToMany('Profile');
    }
}
