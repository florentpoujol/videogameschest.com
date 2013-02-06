<?php
/**
 * class mostly called from the crontab
 * check for promotion emails to be sent
 * then sent them
 */
class Sendpromotionnewsletters_Task 
{
    public function run($arguments)
    {
        $newsletters = PromotionNewsletter::all();

        $now = new DateTime();
        $now_string = $now->format(Config::get('vgc.date_formats.english'));
        
        foreach ($newsletters as $newsletter) {
            // check time
            $last_pub_date = new DateTime($newsletter->last_pub_date);
            $interval = new DateInterval('PT'. $newsletter->frequency .'H');
            $last_pub_date->add($interval);
            
            if ($last_pub_date < $now) {
                $newsletter->last_pub_date = $now;
                $newsletter->save();

                $subject = 'Here is your promotion Newsletter from VideoGamesChest.com on '.$now_string;

                //
                $profiles = Search::make($newsletter->search_id)
                ->where_privacy('public')
                ->where_in_promotion_newsletter(1)
                ->get();

                if ($newsletter->use_blacklist == 1) {
                    $profiles = ProcessBlacklist($profiles, $newsletter->user_id);
                }

                $profiles = PickAtRandomInArray($profiles, $newsletter->profile_count);
                
                $html = View::make('layouts/promotion_newsletter', array(
                    'newsletter' => $newsletter,
                    'profiles' => $profiles,
                    'title' => $subject,
                ))->render();
                
                //
                if ($newsletter->user_id > 0) {
                    $user = User::find($newsletter->user_id);

                    if (is_null($user)) {
                        log::write('promotion newsletter error email', 'Wrong user id='.$newsletter->user_id.' for newsletter id='.$newsletter->id);
                        continue;
                    }

                    $email = $user->email;
                } else {
                    $email = $newsletter->email;
                }

                SendMail($email, $subject, $html);
            }
        }
    }
}
