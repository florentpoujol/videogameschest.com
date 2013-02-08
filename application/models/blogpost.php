<?php

class BlogPost extends ExtendedEloquent
{
    
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
}