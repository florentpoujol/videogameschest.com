<?php

class BlogPost extends ExtendedEloquent
{
    public static function create($input)
    {

        if (trim($input['title_url']) == '') {
            $title_url = $input['title'];
            $title_url = str_replace(' ', '-', $title_url);
            $title_url = strtolower($title_url);
            $input['title_url'] = $title_url;
        }

        return parent::create($input);
    }
    
    public function get_date()
    {
        $date = new DateTime($this->get_attribute('created_at'));

        return $date->format(Config::get('vgc.date_formats.blog'));
    }

    public function get_sidebar_date()
    {
        $date = new DateTime($this->get_attribute('created_at'));

        return $date->format(Config::get('vgc.date_formats.blog_sidebar'));
    }

    public function get_url()
    {
        return route('get_blog_post', array($this->get_attribute('title_url')));
    }

    public function get_parsed_content()
    {
        $content = $this->content;

        // first parse routing helpers
        // (route:routeName[:arg1[,arg2...]])
        $matches = array();

        while (preg_match("#\(route:([a-z_]+)(:([^)]+))?\)#i", $content, $matches) != 0) {
            // $matches[1] is the route name
            // $matches[3], if exists is the coma-separated list of arguments
            $args = array();
            if (isset($matches[3])) {
                $args = explode(',', $matches[3]);

                for ($i = 0; $i < count($args); $i++) {
                    $args[$i] = trim($args[$i]);
                    if ($args[$i] == '') unset($args[$i]);
                }
            }

            $url = route($matches[1], $args);
            $content = str_replace($matches[0], '('.$url.')', $content);
        }

        // then parse markdown
        return Sparkdown\Markdown($content);
    }
}