<?php


//----------------------------------------------------------------------------------
//    HTML
//----------------------------------------------------------------------------------


//----------------------------------------------------------------------------------


/**
 * Get all error/success/infos messages
 * @return string The formated messages
 */
HTML::macro('get_messages', function($view_errors = null) 
{
    $msg = HTML::get_errors($view_errors);
    $msg .= HTML::get_success();
    $msg .= HTML::get_infos();

    return $msg;
});


/**
 * Wraper around validation_errors() of the Form_validation library
 *  that allow to add my own errors mesages
 * @return string The formated error messages
 */
HTML::macro('get_errors', function($view_errors = null) 
{
	$session_errors = Session::get('vgc_errors');
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

    Session::forget('vgc_errors');

    return $errors;
});


/**
 * Register an error to be displayed the next time HTML::get_error() is called
 * @param string $error The error
 */
HTML::macro('set_error', function($error) 
{
    $session_errors = Session::get('vgc_errors');
    
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

    Session::put("vgc_errors", json_encode($session_errors));
});


//----------------------------------------------------------------------------------


/**
 * Wraper around validation_errors() of the Form_validation library
 *  that allow to add my own errors mesages
 * @return string The formated error messages
 */
HTML::macro('get_success', function() 
{
    $json_success = Session::get('vgc_success');
    $html_success = "";

    if ($json_success != "") 
    {
        $html_success .= '<div class="alert alert-success">
        ';

        $array_success = json_decode($json_success, true);
        foreach ($array_success as $success) 
        {
            $html_success .= $success.' <br>
            ';
        }
        
        $html_success .= "</div>
        ";
    }

    Session::forget('vgc_success');

    return $html_success;
});



/**
 * Register a success message to be displayed the next time HTML::get_success() is called
 */
HTML::macro('set_success', function($success) 
{
    $session_success = Session::get('vgc_succes');
    
    if ($session_success != "") 
    {
        $session_success = json_decode($session_success, true);
        $session_success[] = '"'.$success.'"';
        Session::put("vgc_success", json_encode($session_success));
    }
    else
        Session::put("vgc_success", '["'.$success.'"]');
});



//----------------------------------------------------------------------------------

/**
 * Wraper around validation_errors() of the Form_validation library
 *  that allow to add my own errors mesages
 * @return string The formated error messages
 */
HTML::macro('get_infos', function() 
{
    $json_success = Session::get('vgc_infos');
    $html_success = "";

    if ($json_success != "") 
    {
        $html_success .= '<div class="alert alert-info">
        ';

        $array_success = json_decode($json_success, true);
        foreach ($array_success as $success) 
        {
            $html_success .= $success.' <br>
            ';
        }
        
        $html_success .= "</div>
        ";
    }

    Session::forget('vgc_infos');

    return $html_success;
});



/**
 * Register a success message to be displayed the next time HTML::get_success() is called
 */
HTML::macro('set_info', function($success) 
{
    $session_success = Session::get('vgc_infos');
    
    if ($session_success != "") 
    {
        $session_success = json_decode($session_success, true);
        $session_success[] = '"'.$success.'"';
        Session::put("vgc_infos", json_encode($session_success));
    }
    else
        Session::put("vgc_infos", '["'.$success.'"]');
});
//----------------------------------------------------------------------------------
//    FORM
//----------------------------------------------------------------------------------
