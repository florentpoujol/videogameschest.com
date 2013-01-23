<?php

class Report extends ExtendedEloquent
{
    //----------------------------------------------------------------------------------
    // CRUD METHODS

    public static function create($form)
    {
        $form = clean_form_input($form);

        if (isset($form['admin'])) $form['type'] = 'admin';
        else $form['type'] = 'developer';
        unset($form['admin']);
        
        $report = parent::create($form);

        HTML::set_success(lang('reports.msg.create_success'));

        //$type = $form['type'] == 'admin' ? $form['type'] : 'developer';
        // Log::write('report create success '.$type, $type.' report created for  (name : '.);

        return $report;
    }

    /*public static function delete($form) // not delete() can't be static since it is already declared as non static
    {
        $form = clean_form_input($form);
        unset($form['action']);

        foreach ($form['reports'] as $report_id) {
            Report::find($report_id)->delete();
        }

        HTML::set_success(lang('report.msg.delete_success'));
        return $report;
    }*/

    //----------------------------------------------------------------------------------
    // RELATIONSHIPS

    public function profile()
    {
        if ($this->developer_id != 0) return $this->belongs_to('Developer', 'developer_id');
        elseif ($this->game_id != 0) return $this->belongs_to('Game', 'game_id');
        /*
        Why do I need to set the foreign key here ?
        => it seems that the name of the method has an impact on the returned value
        and the foreign key is not needed when the method name match the model name
        */
    }

}
