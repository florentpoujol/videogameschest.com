<?php

class Report extends ExtendedEloquent
{
    //----------------------------------------------------------------------------------
    // CRUD METHODS

    public static function create($input)
    {
        $report = parent::create($input);
        HTML::set_success(lang('reports.msg.create_success'));
        // Log::write('report create success', "User with name='".user()->name."' and id=".user_id()." created the report with id=".$report->id." for the profile with id=".$report->profile->id.".");
        return $report;
    }

    // deletion is done from post_reports_update() in admin controller

    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

    public function profile()
    {
        return $this->belongsTo('profile');
    }
}
