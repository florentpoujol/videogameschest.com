<?php


/*
    HTML
 */

HTML::macro('flash', function()
{
    $message_status = Session::get('message_status');
    $message        = Session::get('message');

    return ($message && $message_status) ? '<div class="flash flash-' . $message_status . '">' . $message . '</div>' : '';
});



//----------------------------------------------------------------------------------
//    FORM
//----------------------------------------------------------------------------------


/**
 * Wraper around validation_errors() of the Form_validation library
 *  that allow to add my own errors mesages
 * @return string The formated error messages
 */
Form::macro('get_errors', function($view_errors = null) 
{
	$session_errors = Session::get('form_errors');
    $errors = "";

    if ($session_errors != "" || (is_object($view_errors) && count($view_errors->all()) > 0) ) 
    {
        $errors .= '<div class="alert alert-error">
        ';

        if ($session_errors != "") 
        {
            $session_errors = json_decode($session_errors, true);
            foreach ($session_errors as $error) 
            {
                $errors .= $error.' <br>
                ';
            }
        }

        if ($view_errors != null) 
        {
            foreach ($view_errors->all() as $error) 
            {
                $errors .= $error.' <br>
                ';
            }
        }

        $errors .= "
        </div>
        ";
    }

    Session::forget('form_errors');

    return $errors;
});



/**
 * Register an error to be displayed the next time Form::get_error() is called
 * @param string $error The error
 */
Form::macro('set_error', function($error) 
{
    $session_errors = Session::get('form_errors');
    
    if ($session_errors != '') {
        $session_errors = json_decode($session_errors, true);
    }
    else {
        $session_errors = array();
    }

    if (is_string($error)) {
        $session_errors[] = '"'.$error.'"';
    }
    elseif (is_array($error)) 
    {
        $errors = $error;
        foreach ($errors as $error) {
            $session_errors[] = '"'.$error.'"';
        }
    }
    else 
    { // error must an object of type Laravel/Message, probably from the Validator class
        $errors = $error;
        foreach ($errors->all() as $error) {
            $session_errors[] = '"'.$error.'"';
        }
    }

    Session::put("form_errors", json_encode($session_errors));
});


//----------------------------------------------------------------------------------


