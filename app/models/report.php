<?php

class Report extends Eloquent
{
    protected $guarded = array();
    
    //----------------------------------------------------------------------------------
    // CRUD METHODS

    public static function create(array $input = array())
    {
        $report = parent::create(clean_form_input($input));
        HTML::set_success(lang('reports.msg.create_success'));
        Log::info("report create success : User with name='".user()->name."' and id=".user_id()." created the report with id=".$report->id." for the profile with id=".$report->profile->id.".");
        return $report;
    }

    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

    public function profile()
    {
        return $this->belongsTo('Profile');
    }
}
