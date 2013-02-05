<?php 
$name = xssSecure($profile->name);
?>
@section('page_title')
    {{ $name }}
@endsection

<div id="game-profile" class="profile">
    <?php
    $background_url = trim(xssSecure($profile->profile_background));
    ?>
    @if ($background_url)
        <div id="profile-background">
            <img src="{{ $background_url }}" alt="Profile background">
        </div>
        <div id="profile-background-pusher"></div>
    @endif

    <!-- name dev website -->
    <div class="row">
        <div class="span5">
            <h3>{{ $name }} <small>{{ $profile->class_name }}</small></h3>
        </div>

        <div class="span3 header-side-column">
            <ul class="unstyled">
                <?php
                if ($profile->developer_id == 0) {
                    $dev_name = trim(xssSecure($profile->developer_name));
                    $dev_url = '';
                } else {
                    $dev_name = xssSecure($profile->dev->name);
                    $dev_url = route('get_developer', array(name_to_url($dev_name)));
                }

                $publisher_name = xssSecure($profile->publishername);
                $publisher_url = xssSecure($profile->publisherurl);
                ?>
                @if ($dev_name != '')
                    @if ($dev_url != '')
                        <li>{{ icon('wrench', lang('common.developer')) }} <a href="{{ $dev_url }}" title="{{ lang('common.developer') }}" class="">{{ $dev_name }}</a></li>
                    @else
                        <li>{{ icon('wrench', lang('common.developer')) }} {{ $dev_name }}</li>
                    @endif
                @endif

                @if ($publisher_name != '')
                    @if ($publisher_url != '')
                        <li>{{ icon('money', lang('common.publisher')) }} <a href="{{ $publisher_url }}" title="{{ $publisher_name }}" class="">{{ $publisher_name }}</a></li>
                    @else
                        <li>{{ icon('briefcase', lang('common.publisher')) }} {{ $publisher_name }}</li>
                    @endif
                @endif
            </ul>
        </div>

        <div class="span4 header-side-column">
            <ul class="unstyled">
                <?php
                $website = trim(xssSecure($profile->website));
                $presskit = trim(xssSecure($profile->presskit));
                $soudtrack = trim(xssSecure($profile->soundtrackurl));
                ?>
                @if ($website != '')
                    <li>{{ icon('globe', lang('common.website')) }}<a href="{{ $website }}" title="{{ lang('common.website') }}">{{ shortenUrl($website) }}</a></li>
                @endif
                
                @if ($presskit != '')
                    <li>{{ icon('folder-open', lang('common.presskit')) }}<a href="{{ $presskit }}" title="{{ lang('common.presskit') }}">{{ lang('common.presskit') }}</a></li>
                @endif

                @if (false && $soudtrack != '')
                    <li>{{ icon('music', lang('common.soundtrack')) }}<a href="{{ $soudtrack }}" title="{{ lang('common.soundtrack') }}">{{ shortenUrl($soudtrack) }}</a></li>
                @endif
            </ul>
        </div>
    </div>

    <hr>

    <!-- blog pitch logo -->
    <div class="row-fluid">
        <div class="span12">
            <?php
            $blogfeed = XssSecure($profile->blogfeed);
            ?>
            @if ($blogfeed != '')
                <div class="span4">
                    <h4>
                        {{ lang('common.profile_blogfeed') }}
                        <a href="{{ $blogfeed }}" title="Blog feed">{{ icon('rss') }}</a>
                    </h4>

                    <ul class="unstyled">
                        <?php
                        $feed = RSSReader::read($blogfeed, Config::get('vgc.profile_blog_feed_item_count'));
                        ?>

                        @foreach ($feed['items'] as $item)
                            <li><a href="{{ $item['link'] }}" title="{{ $item['title'] }}">{{ $item['title'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <img src="{{ xssSecure($profile->cover) }}" alt="{{ $name }} box cover or icon" title="{{ $name }} box cover or icon" class="logo pull-right">

            <i class="icon-quote-left icon-3x pull-left icon-muted"></i>

            {{ $profile->get_parsed_pitch() }}

            <i class="icon-quote-right icon-3x pull-right icon-muted"></i>
        </div>
    </div>

    <hr>
    <!-- MEDIA row -->
    <div class="row">
        <div class="span12">
            <ul class="nav nav-tabs" id="medias-tabs">
                <li><a href="#screenshots-pane" data-toggle="tab">{{ lang('common.screenshots') }}</a></li>
                <li><a href="#videos-pane" data-toggle="tab">{{ lang('common.videos') }}</a></li>
                <li><a href="#soundtrack-pane" data-toggle="tab">{{ lang('common.soundtrack') }}</a></li>
            </ul>

            <div class="tab-content">
                
                <div class="tab-pane" id="screenshots-pane"> 
                    <!-- <h4>{{ lang('common.screenshots') }} <small>{{ lang('game.profile.screenshots_help') }}</small></h4> -->
                    
                    <div id="screenshots-container" class="slider-wrapper theme-light">
                        <div id="screenshots-nivo-slider" class="nivoSlider">
                            <?php
                            $screenshots = $profile->screenshots;
                            for ($i = 0; $i < count($screenshots['names']); $i++):
                                $url = xssSecure($screenshots['urls'][$i]);
                                $title = xssSecure($screenshots['names'][$i]);
                            ?>
                                <a href="{{ $url }}" title="{{ $title }}" class="colorbox-group1">
                                    <img src="{{ $url }}" alt="{{ $title }}" id="gamescreenshot{{ $i }}" title="{{ $title }}">
                                </a>
                            @endfor
                        </div> <!-- /.carousel-inner -->
                    </div> <!-- /#screenshots-container .carousel .slide -->
                </div> <!-- /#screenshots-pane .tab-pane -->

                <div class="tab-pane" id="videos-pane"> 
                    <!-- <h4>{{ lang('common.videos') }}</h4> -->
                
                    <div id="videos-container" class="slider-wrapper theme-light">
                        <div id="videos-nivo-slider" class="nivoSlider">
                            <?php
                            $videos = $profile->videos;
                            ?>
                            @for ($i = 0; $i < count($videos['names']); $i++)
                                <?php
                                $video = new Video($videos['urls'][$i]);
                                $title = xssSecure($videos['names'][$i]);
                                ?>
                                <a href="{{ $video->embed_url }}" title="{{ $title }}" class="gamevideos colorbox-group2">
                                    <img src="{{ $video->thumbnail_url }}" alt="{{ $title }}" title="{{ $title }}" id="gamevideo{{ $i }}">
                                </a>

                            @endfor
                        </div>
                    </div>
                </div> <!-- /#videos-pane .tab-pane -->

                <div class="tab-pane" id="soundtrack-pane">
                    <div id="soundtrack-container">
                        <?php
                        echo DisplaySoundtrack(XssSecure($profile->soundtrack));
                        ?>
                    </div>
                </div> <!-- /#soundtrack-pane .tab-pane -->
            </div> <!-- /.tab-content -->
        </div> <!-- /.span12 -->
    </div> <!-- /.row -->

    <hr>

    <div class="row">
        <div class="span6">
            stores
           
        </div> <!-- /.span8 -->

        <div class="span3">
            press
        </div> <!-- /.span4 -->

        <div class="span3">
            social networks
        </div> <!-- /.span4 -->

    </div> <!-- /.row -->

    <div class="row-fluid json-field-row">
        
        <?php 
        $items = array( 'devices', );
        foreach ($items as $item):
        ?>
            <div class="span3 json-field-div">
                @if ($item == 'reviews')
                    <h4>{{ lang('common.reviews') }}</h4>
                @else
                    <h4>{{ lang($item.'.title') }}</h4>
                @endif

                <ul class="unstyled">
                    @if ($item == 'socialnetworks' || $item == 'stores' || $item == 'reviews')
                        <?php $array = $profile->$item; ?>

                        @for ($i = 0; $i < count($array['names']); $i++)
                            <li><a href="{{ xssSecure($array['urls'][$i]) }}">{{ lang($item.'.'.$array['names'][$i]) }}</a></li>
                        @endfor
                    @else
                        @if (is_array($profile->$item))
                            @foreach ($profile->$item as $name)
                                <li>{{ lang($item.'.'.$name) }}</li>
                            @endforeach
                        @endif
                    @endif
                </ul>
            </div>
        @endforeach 
    
    </div>

    <hr>

    <div class="row-fluid json-field-row">
        
        <?php 
        $items = array('operatingsystems', 'genres', 'themes', 'viewpoints');
        foreach ($items as $item):
        ?>
            <div class="span3 json-field-div">
                <h4>{{ lang($item.'.title') }}</h4>

                <ul class="unstyled">
                    @if (is_array($profile->$item))
                        @foreach ($profile->$item as $name)
                            <li>{{ lang($item.'.'.$name) }}</li>
                        @endforeach
                    @endif
                </ul>
            </div>
        @endforeach 
    </div>

    <hr>
 
    <div class="row-fluid json-field-row">
        
        <?php 
        $items = array('nbplayers', 'tags', 'languages', 'technologies');
        foreach ($items as $item):
        ?>
            <div class="span3 json-field-div">
                <h4>{{ lang($item.'.title') }}</h4>

                <ul class="unstyled">
                    @if (is_array($profile->$item))
                        @foreach ($profile->$item as $name)
                            <li>{{ lang($item.'.'.$name) }}</li>
                        @endforeach
                    @endif
                </ul>
            </div>
        @endforeach 
    </div>

    <hr>

    <!-- Button to trigger modal -->
    <a class="muted accordion-toggle" data-toggle="collapse" href="#collapse-report">
        {{ lang('common.report_profile_link') }}
    </a>
            
    <div id="collapse-report" class="collapse">
        <div class="accordion-inner">
            @include('forms/postreport')
        </div>
    </div>
</div>

@section('cssfiles')
    {{ Asset::container('colorbox')->styles() }}
    {{ Asset::container('nivo-slider')->styles() }}
@endsection

@section('jsfiles')
    {{ Asset::container('colorbox')->scripts() }}
    {{ Asset::container('nivo-slider')->scripts() }}
@endsection

@section('jQuery')
    $('#medias-tabs a:first').tab('show');
    $('#stores-tabs a:first').tab('show');

    $('#screenshots-nivo-slider').nivoSlider();
    $(".colorbox-group1").colorbox({rel:"group1"});
            
    // videos
    $(".gamevideos").colorbox({iframe:true, innerWidth:800, innerHeight:533, rel:"group_video"});
    $('#videos-nivo-slider').nivoSlider()

@endsection

