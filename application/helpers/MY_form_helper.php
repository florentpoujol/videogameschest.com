<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Wraper around validation_errors() of the Form_validation library
 *  that allow to add my own errors mesages
 * @param array The $form array that may contains the "errors" key
 * @return string The formated error messages
 */
function get_form_errors( $form = null ) {
    $userdata_errors = userdata("form_errors");
    $validation_errors = validation_errors(); // get errors from the form_validation class
    $errors = "";

    if ($userdata_errors != "" || $validation_errors != "") {
        $errors .= '<div class="alert alert-error">
        ';

        if ($userdata_errors != "") {
            $userdata_errors = json_decode($userdata_errors, true);
            foreach ($userdata_errors as $error) {
                $errors .= $error.' <br>
                ';
            }
        }

        $errors .= $validation_errors."
        </div>
        ";
    }

    set_userdata("form_errors", "");

    return $errors;
}


//----------------------------------------------------------------------------------

/**
 * Register an error to be displayed the next time get_form_error is called
 * @param string $error The error
 */
function set_form_error($error) {
    $userdata_errors = userdata("form_errors");
    
    if ($userdata_errors != "") {
        $userdata_errors = json_decode($userdata_errors, true);
        $userdata_errors[] = '"'.$error.'"';
        set_userdata("form_errors", json_encode($userdata_errors));
    }
    else
        set_userdata("form_errors", '["'.$error.'"]');
}


//----------------------------------------------------------------------------------


function escape_json_chars($input) {
    $input = str_replace("[", "\[", $input);
    $input = str_replace("]", "\]", $input);
    $input = str_replace("{", "\{", $input);
    $input = str_replace("}", "\}", $input);
    $input = str_replace('"', '\"', $input);
    return $input;
}


// ----------------------------------------------------------------------------------

/**
 * Allow to set and display my own success message
 * @param array The $form array that may contains the "success" key
 * @return string The formated success messages
 */
function get_form_success( $form = null ) {
    $userdata_success = userdata("form_success");
    $success = "";

    if ($userdata_success != "") {
        $success .= '<div class="alert alert-success">
        ';

        $userdata_success = json_decode($userdata_success, true);
        foreach ($userdata_success as $a_success) {
            $success .= $a_success.' <br>
            ';
        }
        
        $success .= "</div>
        ";
    }

    set_userdata("form_success", "");

    return $success;
}



//----------------------------------------------------------------------------------

/**
 * Register a success to be displayed the next time get_form_success is called
 * @param string $succes The succes
 */
function set_form_success($success) {
    $userdata_success = userdata("form_succes");
    
    if ($userdata_success != "") {
        $userdata_success = json_decode($userdata_success, true);
        $userdata_success[] = '"'.$success.'"';
        set_userdata("form_succes", json_encode($userdata_success));
    }
    else
        set_userdata("form_succes", '["'.$success.'"]');
}


// ----------------------------------------------------------------------------------

/**
 * Wrapper around form_input() and form_label() of the Form helper
 */
function form_input_extended( $input, $control_group = true ) {
    if ( ! isset($input["name"])) {
        $input["name"] = 'form['.$input["id"].']';
    }

    $lang = lang($input["lang"]);
    unset($input["lang"]);

    if ( ! isset($input["placeholder"])) {
        $input["placeholder"] = $lang;
    }

    if ( ! isset($input["type"]) || $input["type"] == "text" || $input["type"] == "url") {
        $input["maxlength"] = "255";
    }
    
    $help = isset($input["help"]) ? $input["help"]: "";
    unset($input["help"]);

    $input["class"] = "controls";


    $html = '<label class="control-label" for="'.$input["id"].'">'.$lang.'</label>
    '.form_input($input);

    if ($help != "") {
        $help = '
<span class="help-inline">'.$help.'</span>';
    }

    if ($control_group) {
        $html = 
'<div class="control-group">
    '.$html.$help.'
</div>
<!-- /.control-group '.$input["name"].' -->
';
    }

    return $html;
}


// ----------------------------------------------------------------------------------

/**
 * Make sure that all potential $form keys and $form["data"] keys exists and have a default value
 * @param array $form An assoc array with where criteria or a single key as string
 * @return 
 */
function form_tooltip( $key ) {
    $html = '<img src="'.img_link('tooltip.jpg').'" alt="Tooltip image" class="tooltip" title="'.lang( "tooltip_".$key ).'" >';
    return $html;
}



/* End of file MY_form_helper.php */
/* Location: ./application/helpers/MY_form_helper.php */