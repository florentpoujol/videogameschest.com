<?php

class Report extends ExtendedEloquent
{
    //----------------------------------------------------------------------------------
    // CRUD METHODS

    public static function create($form)
    {
        $form = clean_form_input($form);
        unset($form['action']);

        if (isset($form['admin'])) $form['type'] = 'admin';
        else $form['type'] = 'dev';
        unset($form['admin']);
        
        $report = parent::create($form);

        HTML::set_success(lang('report.msg.create_success'));
        return $report;
    }

    public static function __delete($form)
    {
        $form = clean_form_input($form);
        unset($form['action']);

        foreach ($form['reports'] as $report_id) {
            Report::find($report_id)->delete();
        }

        HTML::set_success(lang('report.msg.delete_success'));
        return $report;
    }
}
