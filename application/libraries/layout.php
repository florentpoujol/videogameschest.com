<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Layout
{
    private $CI;
    private $data;
    //private $headerData;
    //private $tabMenuData;
    private $layout;

    public function __construct() {
        $this->CI =& get_instance(); // =& permet un passage par référence
        //get_instance() permet d'obtenir le super objet de code igniter

        $this->data['headStart'] = '';
        $this->data['metas'] = array();
        $this->data['css'] = array();
        $this->data['headEnd'] = '';


        $this->data['bodyStart'] = '';
        $this->data['js'] = array();
        $this->data['bodyEnd'] = '';


        // liste des page du site
        /*$this->headerData['developpement_current'] = '';
        $this->headerData['tutoriels_current'] = '';
        $this->headerData['autre_sites_current'] = '';
        $this->headerData['contact_current'] = '';*/

        // set default header

        //	Le titre par défaut est composé du nom de la méthode et du nom du contrôleur.
        //	La fonction ucfirst permet d'ajouter une majuscule.
        $this->data['pageTitle'] = ucfirst( $this->CI->router->fetch_method() ).' - '.ucfirst( $this->CI->router->fetch_class() );

        // default layout is "views/layout/default_layout.php"
        $this->layout = 'default_layout';

        // default tracking code
        //$this->SetVisitTrackingCodeView( 'google_analytics_view' );
    }

    
    // ----------------------------------------------------------------------------------

    /**
     *
     */
    public function SetData( $key, $value ) {
        $this->data[$key] = $value;        
        return $this;
    }


    /**
     * Set a custom title for the page
     * @param The page's title
     */
    public function SetTitle( $title ) {
        $this->data['pageTitle'] = $title;
        return $this;
    }

    /**
     * Set meta tags for the page
     * @param The meta tag's name
     * @param The meta tag's content
     */
    public function SetMetas( $name, $content = null ) {
        if( is_string( $name ) )
            $this->data['metas'][] = array( 'name' => $name, 'content' => $content );

        if( is_array( $name ) )
            $this->data['metas'][] = $name;

        return $this;
    }


    /**
     * Set the CSS files to be loaded on that page
     * @param the name of the file
     */
    public function SetCSS( $file ) {
        $this->data['css'][] = CSSLink( $file );
        return $this;
    }


    /**
     * Set the JavaScript files to loaded on that page
     * @param the name of the file
     */
    public function SetJS( $file ) {
        $this->data['js'][] = JSLink( $file );
        return $this;
    }


    // ----------------------------------------------------------------------------------

    /**
     * set the layout file to use
     * default is "views/layout/default_layout.php"
     *
     * @param the layout name
     */
    public function SetLayout( $layout ) {
        if( is_string( $layout ) )
            $this->layout = $layout;

        return $this;
    }


    /**
     * Add a view to the specified hook in the layout
     * Can be called several time to add several views to the same hook
     * Always call before the Load() method
     *
     * @param The layout's hook to which add the view
     * @param The name of the view file
     * @param the date variable to pass to that view
     */
    public function AddView( $hook, $viewName, $data = array() ) {
        $this->data[$hook] .= $this->CI->load->View( $viewName, $data, true ).'
        ';
        return $this;
    }


    /**
     * Load the layout
     * Call after all View() methods
     */
    public function Load() {
        $this->CI->load->View( '../views/layout/'.$this->layout, $this->data );
    }




    /* = = = = = = = = = = = = = = = = = = = =
    TAB SYSTEM
    = = = = = = = = = = = = = = = = = = = = */


    /*// register tabs of the page, update variable $tabMenuData
    // called from the controller's constructor
    // always call before SetCurrentTab() and SetTabMenu()
    public function InitCurrentTab( $tabs )
    {
        foreach( $tabs as $tab )
            $this->tabMenuData[$tab.'_current'] = '';

        return $this;
    }


    // set the current tab, update the value of the proper tabMenuData index
    // always call after SetCurrentTab() and before SetTabMenu()
    public function SetCurrentTab( $tab )
    {
        $this->tabMenuData[$tab.'_current'] = 'tab_menu_current_tab';
        return $this;
    }


    // set the tab menu view
    // must be set in each controller page function, can't be set in the controller's constructor because it includes the $tabMenuData variable, only updated by the 2 previous functions
    // always call after InitCurrentTab() and SetCurrentTab()
    // also set the tab_layout
    public function SetTabMenu( $viewName, $data = array() )
    {
        if( $data == null && $this->tabMenuData != null )
        $data = $this->tabMenuData;

        $this->data['tabMenu'] = $this->CI->load->View( $viewName, $data, true ).'
        ';
        $this->setLayout( 'tab_layout' );
        return $this;
    }*/
}
?>