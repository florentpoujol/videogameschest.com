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
        $email = null;

        if (isset($input['user_id'])) {
            // check if the subscription already exist for this user then redirect to the update
            $email = parent::where_user_id($input['user_id'])->first();

            if ( ! is_null($email)) {
                return;
            }
        }

        // create new email row
        $input['email_key'] = Str::random(40);
        $email = parent::create($input);
            
        // log
        HTML::set_success(lang('discover.msg.email_subscription_success'));

        if ($email->user_id != 0) {
            $msg = "User name='".user()->name."' id='".user_id()."' email='".user()->email."'";
        } else {
            $msg = "Guest email='".$email->email."'";
        }
        $msg .= " subscribed to the promotion newsletter : id='".$email->id."'" ;
        Log::write('create promotion email success', $msg);

        // send email

        return $email;
    }
}
