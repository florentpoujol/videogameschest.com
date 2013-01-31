<?php

class PromotionEmail extends ExtendedEloquent 
{
    public static $table = 'promotion_emails';

    //----------------------------------------------------------------------------------
    // CRUD

    public static function create($input)
    {
        $input = clean_form_input($input);
        $newsletter = null;

        if (is_logged_in()) {
            $input['user_id'] = user_id();
            // check if the subscription already exist for this user then redirect to the update
            $newsletter = parent::where_user_id(user_id())->first();

            if ( ! is_null($newsletter)) { // should not happend since the create form is not displayed if newsletter already exists for this user
                Log::write('create update promotion newsletter error', 'User name='.user()->username.' id='.user_id().' tried to create a new promotion newsletter (id='.$newsletter->id.') but it already existed. Redirecting to upadte');
                static::update($input);
                return;
            }
        }

        // create new email row
        $input['url_key'] = Str::random(40);
        $newsletter = parent::create($input);
            
        // msg
        HTML::set_success(lang('discover.msg.email_subscription_success'));

        if ($newsletter->user_id != 0) {
            $msg = "User name='".user()->name."' id='".user_id()."' email='".user()->email."'";
        } else {
            $msg = "Guest email='".$newsletter->email."'";
        }
        $msg .= " subscribed to the promotion newsletter : id='".$newsletter->id."'" ;
        // Log::write('create promotion newsletter success', $msg);

        // send email
        $subject = lang('emails.promotion_email_subscription_success.subject');

        $params = array();
        if ($newsletter->user_id != 0) {
            $params['email'] = user()->email;
            $params['username'] = user()->username;
            $params['update_link'] = route('get_discover_email_page');
        } else { // is guest
            $params['email'] = $newsletter->email;
            $params['username'] = "";
            $params['update_link'] = route('get_discover_update_email_page', array($newsletter->id, $newsletter->url_key));
        }

        $params['frequency'] = $newsletter->frequency;
        $params['profile_count'] = $newsletter->profile_count;
        $params['search_id'] = $newsletter->search_id;
        $params['use_blacklist'] = $newsletter->use_blacklist;
        
        $html = lang('emails.promotion_email_subscription_success.html', $params);

        sendMail($params['email'], $subject, $html);

        return $newsletter;
    }

    public static function update($input, $arg2)
    {
        $input = clean_form_input($input);
        if ( ! isset($input['use_blacklist'])) $input['use_blacklist'] = 0;

        if (is_logged_in()) {
            $newsletter = parent::where_user_id(user_id())->first();
            
            if (is_null($newsletter)) {
                HTML::set_error(lang('common.msg.error'));
                Log::write('update promotion newsletter error', 'Promotion newsletter for user name='.user()->username.' id='.user_id().' was not found when updating.');
                return false;
            }

            $log_msg = 'User name='.user()->username.' id='.user_id().' successfully updated its promotion newsletter id='.$newsletter->id.'.';
            
        } else {
            $newsletter = parent::where_id($input['newsletter_id'])->where_url_key($input['newsletter_url_key'])->first();

            if (is_null($newsletter)) {
                HTML::set_error(lang('discover.msg.email_id_key_no_match'));
                Log::write('update promotion newsletter error', 'Guest tried to update promotion newsletter but newsletter id='.$input['newsletter_id'].' and url key='.$input['newsletter_url_key'].' did not match.');
                return false;
                // actually that should not happend since this is already checked on page load 
                // and the update form is displayed only when id and key match
            }
            
            unset($input['newsletter_id']);
            unset($input['newsletter_url_key']);

            $log_msg = 'Guest succesfully updated its promotion newsletter id='.$newsletter->id.' email='.$newsletter->email;
        }

        parent::update($newsletter->id, $input);

        HTML::set_success(lang('discover.msg.email_update_success'));
        Log::write('update promotion newsletter success', $log_msg);
    }

    public static function unsubscribe($input) // can't use delete as method name because already exists
    {
        if (is_logged_in()) {
            $newsletter = parent::where_user_id(user_id())->first();
            
            if (is_null($newsletter)) {
                HTML::set_error(lang('common.msg.error'));
                Log::write('delete promotion newsletter error', 'Promotion newsletter for user name='.user()->username.' id='.user_id().' was not found when unsubscribing.');
                return false;
            }

            $log_msg = 'User name='.user()->username.' id='.user_id().' unsubscribed from the promotion newsletter id='.$newsletter->id.'.';
            $newsletter->delete();
        } else {
            $newsletter = parent::where_id($input['newsletter_id'])->where_url_key($input['newsletter_url_key'])->first();

            if (is_null($newsletter)) {
                HTML::set_error(lang('discover.msg.email_id_key_no_match'));
                Log::write('delete promotion newsletter error', 'Guest tried to unsubscribe from promotion newsletter but newsletter id='.$input['newsletter_id'].' and url key='.$input['newsletter_url_key'].' did not match.');
                return false;
                // actually that should not happend since this is already checked on page load 
                // and the update form is displayed only when id and key match
            }
            
            $log_msg = 'Guest succesfully unsubscribed from the promotion newsletter id='.$newsletter->id.' email='.$newsletter->email;
            $newsletter->delete();
        }

        // msg
        HTML::set_success(lang('discover.msg.email_unsubscription_success'));

        // Lo
        Log::write('delete promotion newsletter success', $log_msg);
        
        return true;
    } 
}
