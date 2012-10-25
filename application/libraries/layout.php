<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Layout
{
    private $CI;
    private $data;
    private $layout;

    public function __construct() {
        $this->CI =& get_instance(); // =& permet un passage par référence
        //get_instance() permet d'obtenir le super objet de code igniter

        $this->data['metas'] = array();
        $this->data['css'] = array();

        $this->data['body_views'] = '';
        $this->data['js'] = array();

        //	Le titre par défaut est composé du nom de la méthode et du nom du contrôleur.
        //	La fonction ucfirst permet d'ajouter une majuscule.
        $this->data['page_title'] = ucfirst( $this->CI->router->fetch_method() ).' - '.ucfirst( $this->CI->router->fetch_class() );

        // default layout is "views/layout/default_layout.php"
        $this->layout = 'default_layout';

        // default tracking code
        //$this->SetVisitTrackingCodeView( 'google_analytics_view' );
    }

    
    // ----------------------------------------------------------------------------------

    /**
     * Set the layout data key/value pairs
     * @param string $key   The data key
     * @param mixed $value The data value
     */
    public function set_data( $key, $value ) {
        $this->data[$key] = $value;        
        return $this;
    }


    /**
     * Set a custom title for the page
     * @param string $title The page's title
     */
    public function set_title( $title ) {
        $this->data['page_title'] = $title;
        return $this;
    }


    /**
     * Set meta tags for the page
     * @param string/array $name The meta tag's name or an array with several name/content pairs
     * @param string $content The meta tag's content
     */
    public function set_metas( $name, $content = null ) {
        if (is_string($name))
            $this->data['metas'][] = array('name' => $name, 'content' => $content);
        elseif (is_array($name))
            $this->data['metas'][] = $name;

        return $this;
    }


    /**
     * Set the CSS files to be loaded on that page
     * @param the name of the file
     */
    public function set_css( $file ) {
        $this->data['css'][] = css_link( $file );
        return $this;
    }


    /**
     * Set the JavaScript files to loaded on that page
     * @param string $file The name of the file
     */
    public function set_js( $file ) {
        $this->data['js'][] = js_link( $file );
        return $this;
    }


    // ----------------------------------------------------------------------------------

    /**
     * Set the layout file to use
     * Default is "views/layout/default_layout.php"
     * @param string $layout The layout name
     */
    public function set_layout( $layout ) {
        if (is_string($layout) && trim($layout) != "")
            $this->layout = $layout;

        return $this;
    }


    /**
     * Add a view to the specified hook in the layout
     * Can be called several time to add several views to the same hook
     * Always call before the Load() method
     * @param string $view_name The name of the view file
     * @param array $data The data variable to pass to that view
     * @param string $hook The layout's hook to which add the view
     */
    public function view( $view_name, $data = array(), $hook = 'body_views' ) {
        $this->data[$hook] .= $this->CI->load->view($view_name, $data, true).'
        ';
        return $this;
    }


    /**
     * Load the layout
     * Call after all View() methods
     */
    public function load( $layout = "", $data = null ) {
        if ($layout != "")
            $this->layout = $layout;

        if (is_array($data))
            $this->data = array_merge($this->data, $data);

        $this->CI->load->View( '../views/layout/'.$this->layout, $this->data );
    }
}

/* End of file Layout.php */
/* Location: ./application/libraries/Layout.php */