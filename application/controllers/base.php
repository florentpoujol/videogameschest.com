<?php

class Base_Controller extends Controller {

    /**
     * Catch-all method for requests that can't be matched.
     *
     * @param  string    $method
     * @param  array     $parameters
     * @return Response
     */
    public function __call($method, $parameters)
    {
        return Response::error('404');
    }


    //----------------------------------------------------------------------------------

    public $layout = 'layouts.main';
    public $restful = true;

    //----------------------------------------------------------------------------------

    /**
     * Set some constant
     */
    public function __construct() 
    {
        parent::__construct();

        /*$title = '';
        switch (ACTION)
        {
            case 'index': $title = Str::title(CONTROLLER);
            break;

            case 'login': $title = lang('menu.login.submit');
            break;

            case 'adduser': $title = 'Add a user account';
            break;
            case 'edituser': $title = 'Edit a user account';
            break;

            case 'adddeveloper': $title = lang('developer.add.title');
            break;
            case 'editdeveloper': $title = lang('developer.edit.title');
            break;

            case 'addgame': $title = lang('game.add.title');
            break;
            case 'editgame': $title = lang('game.edit.title');
            break;

            default: $title = Str::title(ACTION);
        }

        $this->layout
        ->with('page_content', '')
        ->with('page_title', $title)
        ;*/
    }
}