<?php

class Feed_Controller extends Base_Controller
{
    public $layout = null; // prevent the main layout to be automatically included

    //----------------------------------------------------------------------------------

    public function getFeed()
    {
        $feed = Feed::make();

        $feed
        //->logo(asset('logo.png'))
        //->icon(URL::home().'favicon.ico')
        ->webmaster('VideoGamesChest contact@videogameschest.com '.URL::base())
        //->author   ('VideoGamesChest contact@videogameschest.com '.URL::base())
        //->rating('SFW')
        //->pubdate(time())
        //->ttl(60)
        
        
        ->copyright('(c) '.date('Y').' VideoGamesChest.com')
        
        //->category('PHP')
        //->language(Session::get('language', Config::get('language', 'en')))
        ->baseurl(URL::home());

        return $feed;
    }

    public function publish($feed_type, $feed)
    {
        if ($feed_type == 'atom') $feed->Atom();
        else $feed->Rss20();
    }

    //----------------------------------------------------------------------------------


    public function get_reports_feed($url_key)
    {
        $feed_type = 'rss';
        $user = User::where_url_key($url_key)->first();
        $reports = array();
        
        if ( ! is_null($user)) {
            $reports = Report::order_by('created_at', 'desc')->get();

            $feed = $this->getFeed()
                ->title('Report feed')
                ->permalink(route('get_reports_feed', array($url_key)));

            foreach ($reports as $report) {
                $feed->entry()
                    ->published($report->created_at)
                    ->updated($report->updated_at)
                    ->permalink('report id '.$report->id)

                    ->title('New report on profile "'.$report->profile->name.'"')

                    ->content()
                        ->add('text', 
                            $report->message.'
                            See all your reports on VideoGamesChest.com : '.route('get_reports')
                        )->up()

                    ->content()    
                        ->add('html', 
                            $report->message.' <br>
                            <a href="'.route('get_reports').'">See all your reports on VideoGamesChest.com</a> <br>'
                        )->up()
                ;
            }

            $this->publish($feed_type, $feed);
            // return Response::view('rss', array('feed_data' => $feed_data));
        } else {
           return 'Unknow url key.';
        }
    }
}
