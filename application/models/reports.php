<?php

class Reports extends ExtendedEloquent
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
}
