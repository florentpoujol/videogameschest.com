<?php

class AdminController extends BaseController 
{
    public function getIndex()
    {
        $this->layout->nest('page_content', 'logged_in/adminhome');
    }


    //----------------------------------------------------------------------------------
    // LOGIN

    public function getLoginPage() 
    {
        $this->layout->nest('page_content', 'login');
    }

    public function postLogin() 
    {
        $rules = array(
            "username" => "required|min:2",
            "password" => "required|min:5",
        );

        $validation = Validator::make(Input::all(), $rules);
       
        if ($validation->passes()) {
            //var_dump("valid ok");
             
            $username = Input::get("username", '');
            $field = "username";
            
            if (strpos($username, "@")) { // the name is actually an email
                $field = "email";
            }
            
            $user = User::where($field, '=', $username)->first();
            // var_dump($user);
            if ($user != null) {
                if (Auth::attempt(array('username' => $user->username, 'password' => Input::get('password')))) {
                    $keep_logged_in = Input::get('keep_logged_in', '0');
                    $cookie = null;
                    if ($keep_logged_in == '1') {
                        $cookie = Cookie::forever('vgc_user_logged_in', $user->id, 43200); //43200 min = 1 month
                    }

                    HTML::set_success(lang('login.msg.login_success', array(
                        'username' => $user->username
                    )));
                    Log::info('user login : User '.$user->username.' (id='.$user->id.') has logged in');

                    if ($cookie != null)
                        return Redirect::route('get_home_page')->withCookie($cookie);
                    else
                        return Redirect::route('get_home_page');
                } else {
                    HTML::set_error(lang('login.msg.wrong_password', array(
                        'field' => $field,
                        'username' => $username
                    )));
                }
            } else {
                HTML::set_error(lang('login.msg.user_not_found', array(
                    'field' => $field,
                    'username' => $username
                )));
            }
        }
 
        return Redirect::route('get_login_page')->withErrors($validation)->withInput();
    }

    public function getLogout()
    {
        HTML::set_success(lang('login.msg.logout_success'));
        $user = user();
        Log::info('user logout : User '.$user->username.' (id='.$user->id.') has logged out.');
        Cookie::forget('vgc_user_logged_in');
        Auth::logout();
        return Redirect::route('get_login_page');
    }

    //----------------------------------------------------------------------------------
    // LOST PASSWORD

    public function postLostpassword()
    {
        $input = Input::all();
        
        $rules = array(
            'lost_password_username' => 'required|min:5',
            'city' => 'honeypot',
        );
        $validation = Validator::make($input, $rules);
        
        if ($validation->passes()) {
            $username = $input["lost_password_username"];
            $field = "username";

            if (strpos($username, "@")) { // the name is actually an email
                $field = "email";
            }
            
            $user = User::where($field, '=', $username)->first();
            
            if ($user != null) {
                $user->setNewPassword(1); // step 1 : send conf email to user
            } else {
                HTML::set_error(lang('login.msg.user_not_found', array(
                    'field' => $field,
                    'username' => $username
                )));
            }
        }
            
        return Redirect::route('get_login_page')->withErrors($validation)->withInput();
    }

    public function getLostpasswordConfirmation($user_id, $url_key)
    {
        $user = User::where_id($user_id)->where_url_key($url_key)->first();

        if (is_null($user)) {
            $msg = lang('lostpassword.msg.confirmation_error', array(
                'id' => $user_id,
                'url_key' => $url_key,
            ));
            HTML::set_error($msg);
            Log::info('user lostpassword confirmation error : '.$msg);

            return Redirect::route('get_home_page');
        }

        // if user is found
        $user->setNewPassword(2); // setp 2 : generate new password then send by mail

        return Redirect::route('get_login_page');
    }


    // ----------------------------------------------------------------------------------
    // ADD USER

    public function getUserCreate()
    {
        $this->layout->nest('page_content', 'logged_in/createuser');
    }

    public function postUserCreate()
    {
        $input = Input::all();
        
        // checking form
        $rules = array(
            'username' => 'required|min:5|alpha_dash|unique:users',
            'email' => 'required|min:5|email|unique:users',
            'password' => 'required|min:5|confirmed',
            'password_confirmation' => 'required|min:5',
            // 'type' => 'required|in:dev,admin'
        );
        $validation = Validator::make($input, $rules);
        
        if ($validation->passes()) {
            $user = User::create( $input );
            return Redirect::route('get_user_update', array($user->id));
        } else {
            Former::withErrors($validation);
            $this->layout->nest('page_content', 'logged_in/createuser');
        }
    }


    //----------------------------------------------------------------------------------
    // EDIT USER

    public function getUserUpdate($user_id = null)
    {
        if ($user_id == null || ( ! is_admin() && $user_id != user_id()))
            return Redirect::route('get_user_update', array(user_id()));

        if (User::find($user_id) == null) {
            HTML::set_error("Can't find user with id '$user_id' ! Using your user id '".user_id()."'.");
            return Redirect::route('get_user_update', array(user_id()));
        }

        $this->layout->nest('page_content', 'logged_in/updateuser', array('user_id'=>$user_id));
    }

    public function postUserUpdate()
    {
        $input = Input::all();
        if ( ! is_admin()) $input['id'] = user_id();
        $user = User::find($input['id']);
        
        $rules = array(
            'username' => 'required|min:5|alpha_dash',
            'email' => 'required|min:5|email',
        );
        $validation = Validator::make($input, $rules);
        
        if ($validation->fails() || !$user->update($input)) {
            return Redirect::route('get_user_update', array($user->id))
            ->withErrors($validation)
            ->withInput(Input::except(array('password', 'password_confirmation', 'old_password')));
        }

        return Redirect::route('get_user_update', array($user->id));
    }

    public function postPasswordUpdate()
    {
        $input = Input::all();
        if ( ! is_admin()) $input['id'] = user_id();
        $user = User::find($input['id']);

        $input['password'] = trim($input['password']);
        
        // checking form
        if ($input['password'] != '') {
            $old_password_ok = true;
            if ( ! Hash::check($input['old_password'], $user->password)) {
                $old_password_ok = false;
                HTML::set_error(lang('user.msg.wrong_old_password'));
            }

            $rules = array(
                'password' => 'required|min:5|confirmed',
                'password_confirmation' => 'required|min:5',
                'old_password' => 'required|min:5',
            );
            $validation = Validator::make($input, $rules);
        
            if (! $old_password_ok || $validation->fails()) {
                return Redirect::route('get_user_update', array($user->id))
                ->withErrors($validation)
                ->withInput(Input::except(array('password', 'password_confirmation', 'old_password')));
            }

            $user->update($input);
        }

        return Redirect::route('get_user_update', array($user->id));
    }


    //----------------------------------------------------------------------------------
    // ADD PROFILE
    
    public function getProfileCreate()
    {
        $this->layout->nest('page_content', 'logged_in/createprofile');
    }

    public function postProfileCreate()
    {
        $input = Input::all();
        $rules = Config::get('profiles_post_create_rules', array());
        $validation = Validator::make($input, $rules);
        
        if ($validation->passes()) {
            // dd($input);

            $profile = Profile::create($input);
            return Redirect::route('get_profile_update', array($profile->id));
        }

        return Redirect::back()->withErrors($validation)->withInput();
    }


    //----------------------------------------------------------------------------------
    // EDIT PROFILE

    public function postProfileSelect()
    {
        $input = Input::all();
        $name = $input['name'];
        $id = null;
        
        if (is_numeric($name)) {
            if (Profile::find($name) === null) {
                HTML::set_error(lang('profile.msg.profile_not_found', array(
                    'field_name' => 'id',
                    'field_value' => $name,
                )));
            } else { // $id is set in an else block so that it stays null when there is an error, and user is redirected to the select form
                $id = $name;
            }
        } else {
            $profile = Profile::whereName($name)->first();
            
            if ($profile === null) {
                HTML::set_error(lang('profile.msg.profile_not_found', array(
                    'field_name' => 'name',
                    'field_value' => $name,
                )));
            } else {
                $id = $profile->id;
            }
        }

        return Redirect::route('get_profile_update', array($id));
    }

    public function getProfileUpdate($profile_id = null)
    {
        if ($profile_id == null) {
            $this->layout->nest('page_content', 'forms/profile_select');
            return;
        }

        $profile = Profile::find($profile_id);

        if ($profile === null) {
            HTML::set_error(lang('profile.msg.profile_not_found', array(
                'field_name' => 'id',
                'field_value' => $profile_id
            )));

            return Redirect::route('get_profile_update');
        }

        $this->layout->nest('page_content', 'logged_in/updateprofile', array('profile_id' => $profile_id));
    }

    public function postProfileUpdate() 
    {
        $input = Input::all();
        
        // checking form
        $rules = Config::get('vgc.profiles_post_update_rules');
        $validation = Validator::make($input, $rules);
        $profile = Profile::find($input['id']);

        if ( ! $validation->passes() || ! $profile->update($input)) {
            // Input::flash(); // 14/10 WHY ? o 
            return Redirect::route('get_profile_update', array($input['id']))->withErrors($validation)->withInput();
        }

        return Redirect::route('get_profile_update', array($input['id']));
    }


    //----------------------------------------------------------------------------------
    // VIEW PROFILE

    public function getProfileView($profile_id = null)
    {        
        $profile = Profile::find($profile_id);
        
        if (is_null($profile)) {
            HTML::set_error(lang('profile.msg.profile_not_found', array(
                'field_name' => 'id',
                'field_value' => $profile_id
            )));

            return Redirect::route('get_home_page');
        }

        if ($profile->is_public || is_admin()) {
            $this->layout
            ->with('profile', $profile)
            ->nest('page_content', 'profile', array('profile' => $profile));
        } else {
            HTML::set_error(lang('common.msg.access_not_allowed', array('page' => ' profile '.$profile_id)));
            return Redirect::route('get_home_page');
        }
    }


    //----------------------------------------------------------------------------------
    // REPORTS

    public function getReports()
    {
        $this->layout->nest('page_content', 'logged_in/reports');
    }

    public function postReportsCreate()
    {
        $input = Input::all();
        $rules = array(
            'message' => 'required|min:10',
        );
        if (is_guest()) $rules['city'] = 'honeypot';
        $validation = Validator::make($input, $rules);

        if ($validation->passes()) {
            Report::create($input);
            return Redirect::back();
        }

        return Redirect::back()->withErrors($validation)->withInput();
    }

    public function postReportsUpdate()
    {
        $reports = Input::get('reports', array());

        if ( ! empty($reports) ) {
            $count = 0;
            foreach ($reports as $report_id) {
                Report::find($report_id)->delete();
                $count++;
            }
            
            HTML::set_success(lang('reports.msg.delete_success'));
            Log::info("report delete success User with name='".user()->name."' and id=".user_id()." deleted $count reports.");
        }
        
        return Redirect::back();
    }


    //----------------------------------------------------------------------------------
    // SUGGESTIONS

    public function getSuggestions()
    {
        $this->layout->nest('page_content', 'suggestions');
    }

    public function postSuggestionFeedsUpdate() 
    {
        $input = Input::all();
        $feed_ids_to_read = array();

        if (isset($input['add_new_feed']) && trim($input['new_feed_url']) != '') {
            $validation = Validator::make( $input, array('new_feed_url' => 'url') );
            if ( $validation->passes() )
                SuggestionFeed::create( array( 'url' => $input['new_feed_url'] ) );
            else
                return Redirect::back()->withErrors( $validation )->withInput();
        }
        elseif ( ! isset($input['feeds']) ) { // no feeds, deactivate any other actions
            HTML::set_error("No feeds to works with");
            return Redirect::route('get_suggestions_page');
        }
        elseif (isset($input['read_all_feeds'])) {
            $feed_ids_to_read = array_keys($input['feeds']);
        }
        elseif (isset($input['update'])) {
            foreach ($input['feeds'] as $id => $feed) {
                $db_feed = SuggestionFeed::find($id);

                if (trim($feed['url']) == '') {
                    $db_feed->delete();
                }
                elseif ( $db_feed->url != trim($feed['url']) ) {
                    $validation = Validator::make( $feed, array('url' => 'url') );
                    if ( $validation->passes() )
                        $db_feed->update($feed);
                    else
                        return Redirect::back()->withErrors( $validation )->withInput();
                }
            }
        }
        elseif (isset($input['read'])) {
            foreach ($input['feeds'] as $id => $feed) {
                if (isset($feed['read']) && $feed['read'] == '1') // checkbox checked
                    $feed_ids_to_read[] = $id;
            }
        }

        foreach ($feed_ids_to_read as $id) {
            SuggestionFeed::find($id)->read();
        }

        return Redirect::route('get_suggestions_page');
    }



    public function postSuggestionCreate()
    {
        $url = Input::get('url');
        $validation = Validator::make(Input::all(), array('url' => 'required|url'));

        if ($validation->passes()) {
            if ( Suggestion::whereUrl( $url )->first() !== null ) {
                HTML::set_error(lang('suggestion.msg.url_already_suggested'));
            } else {
                Suggestion::create(array(
                    'url' => $url,
                    'source' => 'user',
                ));
            }
        }

        return Redirect::back()->withInput()->withErrors($validation);
    }

    public function postSuggestionsUpdate()
    {
        $input = Input::all();        

        if (isset($input['update_displayed_status'])) {
            if ( !isset($input['display_status']) )
                $input['display_status'] = array();
            // dd($input['display_status']);
            $cookie = Cookie::forever('vgc_suggestions_status_to_display', json_encode( $input['display_status'] ));
            return Redirect::route('get_suggestions_page')->withCookie($cookie);
        }
        elseif ( isset( $input['update_status'] ) ) {
            foreach ($input['status_by_ids'] as $id => $status) {
                $suggestion = Suggestion::find($id);

                if ($suggestion->status != $status) {
                    if ($status == 'delete') 
                        $suggestion->delete();
                    else
                        $suggestion->update(array('status' => $status));
                }
            }
        }
        elseif ( isset( $input['crawl'] ) && isset( $input['suggestion_id_to_crawl'] ) ) {
            $id = $input['suggestion_id_to_crawl'];
            $suggestion = Suggestion::find($id);
            HTML::set_success("crawling suggestion with id '".$suggestion->id."' and url ".$suggestion->url);
            // $profile_data = Crawler::crawl( $suggestion->url );
            
            // $profile = Profile::create( $profile_data );

            $suggestion->update( array(
                'status' => 'added-by-crawler'
                // 'profile_id' => $profile->id,
            ) );
        }

        return Redirect::route('get_suggestions_page');
    }

    //----------------------------------------------------------------------------------
    // TAGS

    public function getTags()
    {
        $this->layout->nest('page_content', 'tags');
    }

    public function postTagsUpdate() 
    {
        $input = Input::all();

        if ( trim($input['new_tags']) != '' ) {
            $tags = explode( ",", $input['new_tags'] );
            foreach ( $tags as $tag ) {
                $tag = trim($tag);
                if ($tag != '')
                    Tag::create( array( 'name' => $tag ) );
            }
        }

        foreach ($input['tags'] as $id => $name) {
            $tag = Tag::find($id);
            $name = trim($name);

            if ($name == '')
                $tag->delete();
            elseif ( $tag->name != $name )
                $tag->update( array( 'name'=>$name ) );
        }
        
        return Redirect::route('get_tags_page');
    }
} // end of Admin controller class
