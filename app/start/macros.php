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
    // return 'no messages';
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
	$session_data = Session::get('vgc_errors', null);
    $html = "";

    if ($session_data !== null || (is_object($view_errors) && count($view_errors->all()) > 0) ) {
        $html .= '<div class="alert alert-error"><a class="close" data-dismiss="alert" href="#">&times;</a>
        ';

        if ($session_data !== null) {
            $msgs = unserialize($session_data);
            
            foreach ($msgs as $msg) {
                $html .= $msg.' <br>
                ';
            }
        }

        if ($view_errors !== null) {
            foreach ($view_errors->all() as $msg) {
                $html .= $msg.' <br>
                ';
            }
        }

        $html .= "
        </div>
        ";
    }

    Session::forget('vgc_errors');

    return $html;
});


/**
 * Register an error to be displayed the next time HTML::get_error() is called
 * @param string $error The error
 */
HTML::macro('set_error', function($error) 
{
    $session_data = Session::get('vgc_errors', null);
    
    if ($session_data !== null) {
        $msgs = unserialize($session_data);
    } else $msgs = array();
    

    if (is_string($error)) {
        $msgs[] = $error;
    } elseif (is_array($error)) {
        $errors = $error;
        foreach ($errors as $error) {
            $msgs[] = $error;
        }
    } else { // error must be an object of type Laravel/Message, probably from the Validator class
        $errors = $error;
        foreach ($errors->all() as $error) {
            $msgs[] = $error;
        }
    }

    Session::put("vgc_errors", serialize($msgs));
});


//----------------------------------------------------------------------------------


/**
 * Wraper around validation_errors() of the Form_validation library
 *  that allow to add my own errors mesages
 * @return string The formated error messages
 */
HTML::macro('get_success', function() 
{
    $session_data = Session::get('vgc_success', null);
    $html = "";

    if ($session_data !== null) {
        $html .= '<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#">&times;</a>
        ';
        
        $msgs = unserialize($session_data);
        
        foreach ($msgs as $msg) {
            $html .= $msg.' <br>
            ';
        }
        
        $html .= "</div>
        ";
    }

    Session::forget('vgc_success');

    return $html;
});



/**
 * Register a success message to be displayed the next time HTML::get_success() is called
 */
HTML::macro('set_success', function($msg) 
{
    $session_data = Session::get('vgc_success', null);
    
    if ($session_data !== null) 
    {
        $session_data = unserialize($session_data);
        $session_data[] = $msg;
        Session::put("vgc_success", serialize($session_data));
    }
    else
        Session::put("vgc_success", serialize(array($msg)));
});



//----------------------------------------------------------------------------------

/**
 * Wraper around validation_errors() of the Form_validation library
 *  that allow to add my own errors mesages
 * @return string The formated error messages
 */
HTML::macro('get_infos', function() 
{
    $session_data = Session::get('vgc_info', null);
    $html = "";

    if ($session_data !== null) {
        $html .= '<div class="alert alert-info"><a class="close" data-dismiss="alert" href="#">&times;</a>
        ';
        
        $msgs = unserialize($session_data);
        
        foreach ($msgs as $msg) {
            $html .= $msg.' <br>
            ';
        }
        
        $html .= "</div>
        ";
    }

    Session::forget('vgc_info');

    return $html;
});



/**
 * Register a success message to be displayed the next time HTML::get_success() is called
 */
HTML::macro('set_info', function($msg) 
{
    $session_data = Session::get('vgc_info', null);
    
    if ($session_data !== null) 
    {
        $session_data = unserialize($session_data);
        $session_data[] = $msg;
        Session::put("vgc_info", serialize($session_data));
    }
    else
        Session::put("vgc_info", serialize(array($msg)));
});


//----------------------------------------------------------------------------------
//    FORM
//----------------------------------------------------------------------------------
