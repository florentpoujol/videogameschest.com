<?php

class Report extends ExtendedEloquent
{
    //----------------------------------------------------------------------------------
    // CRUD METHODS

    public static function create($input)
    {
        $report = parent::create($input);
        HTML::set_success(lang('reports.msg.create_success'));
        Log::write('report create success', "User with name='".user()->name."' and id=".user_id()." created the report with id=".$report->id." for the profile with id=".$report->profile->id.".");
        return $report;
    }

    // deletion is done from post_reports_update() in admin controller

    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

    public function profile()
    {
        return $this->belongs_to('profile', 'profile_id');
        /*
        Why do I need to set the foreign key here ?
        => it seems that the name of the method has an impact on the returned value
        and the foreign key is not needed when the method name match the model name
        */
    }
}
