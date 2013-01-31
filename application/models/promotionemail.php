<?php

class PromotionEmail extends ExtendedEloquent 
{
    public static $table = 'promotion_emails';

    //----------------------------------------------------------------------------------

    /**
     * Create a email row
     * @todo Check emails that are not used  :    NOW - updated_at > frequency
     */
    public static function create($input)
    {
        $input = clean_form_input($input);
        $newsletter = null;

        if (isset($input['user_id'])) {
            // check if the subscription already exist for this user then redirect to the update
            $newsletter = parent::where_user_id($input['user_id'])->first();

            if ( ! is_null($newsletter)) {
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
        Log::write('create promotion newsletter success', $msg);

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

        return $email;
    }

    public static function update($input)
    {

    }

    public static function unsubscribe($input) // can't use delete as method name because already exists
    {
        if (is_logged_in()) {
            $newsletter = PromotionEmail::where_user_id($input['user_id']);
            //$newsletter = user()->PromotionEmail;
            
            if (is_null($newsletter)) {
                HTML::set_error(lang('common.msg.error'));
                Log::write('delete promotion newsletter error', 'Promotion newsletter for user name='.user()->username.' id='.user_id().' was not found when unsubscribing with id='.$input['user_id'].'.');
                return false;
            }

            $newsletter->delete();
            $log_msg = 'User name='.user()->username.' id='.user_id().' unsubscribed from the promotion newsletter.';
        } else {
            $newsletter = PromotionEmail::where_id($input['newsletter_id'])->where_url_key($input['newsletter_url_key']);

            if (is_null($newsletter)) {
                HTML::set_error(lang('discover.msg.email_id_key_no_match'));
                Log::write('delete promotion newsletter error', 'Guest tried to unsubscribe from promotion newsletter but newsletter id='.$input['newsletter_id'].' and url key='.$input['newsletter_url_key'].' did not match.');
                return false;
                // actually that should not happend since this is already checked on page load 
                // and the update form is displayed only when id and key match
            }
            
            $log_msg = 'Guest succesfully unsubscribed from the promotion newsletter id='.$newsletter->id.' email='.$newsletter->email.'.';
            $newsletter->delete();
        }

        // msg
        HTML::set_success(lang('discover.msg.email_unsubscription_success'));

        // Log
        Log::write('delete promotion newsletter success', $log_msg);
                
        return true;
    }
}
