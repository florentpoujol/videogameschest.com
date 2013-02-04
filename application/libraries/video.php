<?php

class Video
{
    public $id = '';
    public $link = '';
    public $embed_url = '';
    public $thumbnail_url = '';

    public function __construct($link)
    {
        $this->link = $link;

        /* youtube
        standard urls : youtube.com/watch?v=B0ewUjc3wbs  or   youtu.be/B0ewUjc3wbs  
        embed : http://www.youtube.com/embed/B0ewUjc3wbs http://youtube.googleapis.com/v/0l9oOGCie4w

        youtube thumbnail :
        http://stackoverflow.com/questions/2068344/how-to-get-thumbnail-of-youtube-video-link-using-youtube-api
        http://img.youtube.com/vi/<insert-youtube-video-id-here>/0.jpg
        http://img.youtube.com/vi/<insert-youtube-video-id-here>/1.jpg
        http://img.youtube.com/vi/<insert-youtube-video-id-here>/2.jpg
        http://img.youtube.com/vi/<insert-youtube-video-id-here>/3.jpg

        http://img.youtube.com/vi/<insert-youtube-video-id-here>/default.jpg
        http://img.youtube.com/vi/<insert-youtube-video-id-here>/hqdefault.jpg
        http://img.youtube.com/vi/<insert-youtube-video-id-here>/mqdefault.jpg
        http://img.youtube.com/vi/<insert-youtube-video-id-here>/maxresdefault.jpg
         */
        if (strpos($link, 'youtu') !== false) {
            $this->id = preg_replace("#.*(/watch\?v=|\.be/|/v/|/embed/)([a-zA-Z0-9]+)(&|/)?.*#", '$2', $link);
            $this->embed_url = 'http://www.youtube.com/embed/'.$this->id;
            $this->thumbnail_url = 'http://img.youtube.com/vi/'.$this->id.'/maxresdefault.jpg';
        } 


        // vimeo
        // stadard : http://vimeo.com/57183688
        // embed : http://player.vimeo.com/video/57183688
        // API : http://developer.vimeo.com/apis/simple#video-request
        // http://vimeo.com/api/v2/video/{id}.(xml|php|json)
        // [0][thumbnail_(small|medium|large)]
        elseif (strpos($link, 'vimeo.com') !== false) {
            $this->id = preg_replace("#.*vimeo\.com/(video/)?([0-9]{8}).*#", '$2', $link);
            $this->embed_url = 'http://player.vimeo.com/video/'.$this->id.'?title=0&amp;byline=0&amp;portrait=0';
            $json = json_decode(file_get_contents('http://vimeo.com/api/v2/video/'.$this->id.'.json'), true);
            $this->thumbnail_url = $json[0]['thumbnail_large'];
        }


        // dailymotion
        // standard : http://www.dailymotion.com/video/{id}_{long text}
        // embed : http://www.dailymotion.com/embed/video/{id}
        // API : http://www.dailymotion.com/doc/api/obj-video.html
        //https://api.dailymotion.com/video/{id}?fields=field1,field2,
        elseif (strpos($link, 'dailymotion.com') !== false) {
            $this->id = preg_replace("#.*video/([a-zA-Z0-9]+)(_.*)?#", '$1', $link);
            $this->embed_url = 'http://www.dailymotion.com/embed/video/'.$this->id;
            $json = json_decode(file_get_contents('https://api.dailymotion.com/video/'.$this->id.'?fields=thumbnail_large_url'), true);
            $this->thumbnail_url = $json['thumbnail_large_url'];
        }
    }

}