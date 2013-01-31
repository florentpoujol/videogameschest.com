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
        Log::write('create promotion email success', $msg);

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
}
