<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Featured extends MY_Controller {
    
    function index()
    {

        $input = "
<div id=\"report_form\">
    <fieldset>
        <legend>{{ lang(\"report_title\") }}</legend>
        
        <!-- report form errors and success -->
        {{ get_form_errors($this->session->flashdata(\"report_errors\")) }}
        {{ get_form_success($this->session->flashdata(\"report_success\")) }}
        <!-- /report form errors and success -->
<?php
// extract profile id from the url
$uri_strings = explode("/", uri_string()); // uri_string return the actual URL, not the rerouted one
$profile_id = $uri_strings[1];
?>          
        {{ form_open(\"admin/reports\") }}
            <label for=\"description\">{{ lang(\"report_description\") }}</label> <br>
            <textarea name=\"report_form[description]\" id=\"description\" placeholder=\"{{ lang(\"report_description_placeholder\") }}\"></textarea> <br>
            <br>
            {{ lang(\"report_recipient\") }} :<br>
            <input type=\"radio\" name=\"report_form[type]\" value=\"dev\" id=\"report_developer\" checked=\"checked\"> <label for=\"report_developer\">{{ lang(\"report_developer\") }}></label> <br>
            <input type=\"radio\" name=\"report_form[type]\" value=\"admin\" id=\"report_admin\"> <label for=\"report_admin\">{{  lang(\"report_admin\") }}></label> <br>
            <br>
            <?php echo '<input type=\"hidden\" name=\"report_form[profile_id]\" value=\"'.$profile_id.'\">'; ?> 
            <?php echo '<input type=\"hidden\" name=\"report_form[url]\" value=\"'.uri_string().'\">'; ?> 
            <input type=\"submit\" name=\"new_report_form_submitted\" value=\"{{ lang(\"report_submit\") }}\">
        </form>
    </fieldset>
</div> 
<!-- /#report_form -->
";

        echo $this->mytemplateengine->parse($input);
    }

    function pagenotfound( $reason = "noreason" ) {
        $data = array();
        $reasons = preg_split("#:#", $reason);
        $_404 = array();

        switch( $reasons[0] ) {
            case "gamenotfound": $_404['reason'] = 'The game with id or name ['.$reasons[1].'] was not found !';
            break;
        }
        
        $this->layout
        ->AddView( 'bodyStart', 'menu_view', array('page'=>'featured404'))
        ->AddView( 'bodyStart', '404_view', $_404)
        ->AddView( 'bodyStart', 'featured_view', $data )
        ->Load();
    }
}

/* End of file featured.php */
/* Location: ./application/controllers/featured.php */