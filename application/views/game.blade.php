<?php 
if ( ! isset($preview)) $preview = false;

$name = $profile->name;
?>
@section('page_title')
    {{ $name }}
@endsection

<div id="game-profile" class="profile">
    <?php
    $background_url = trim($profile->profile_background);
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
            <h3>{{ $name }} <small>{{ $profile->type }}
                @if ($preview)
                    preview
                @endif
            </small></h3>
        </div>

        <div class="span3 header-side-column">
            <ul class="unstyled">
                <?php
                if ($profile->developer_id == 0) {
                    $dev_name = trim($profile->developer_name);
                    $dev_url = '';
                } else {
                    $dev_name = $profile->dev->name;
                    $dev_url = route('get_profile_view', array('developer', name_to_url($dev_name)));
                }

                $publisher_name = $profile->publishername;
                $publisher_url = $profile->publisherurl;
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
                $website = trim($profile->website);
                $presskit = trim($profile->presskit);
                $soudtrack = trim($profile->soundtrackurl);
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
            $blogfeed = $profile->blogfeed;
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


            <img src="{{ $profile->cover }}" alt="{{ $name }} box cover or icon" title="{{ $name }} box cover or icon" class="logo pull-right">

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
                    
                    <div id="screenshots-container" >
                        <div id="screenshots-slider-wrapper" class="coda-slider-wrapper">
                            <div id="screenshots-slider" class="coda-slider">
                                <?php
                                $screenshots = $profile->screenshots;
                                for ($i = 0; $i < count($screenshots['names']); $i++):
                                    $url = $screenshots['urls'][$i];
                                    $title = $screenshots['names'][$i];
                                ?>
                                    <div>
                                        <a href="{{ $url }}" title="{{ $title }}" class="screenshots-group">
                                            <img src="{{ $url }}" alt="{{ $title }}" id="gamescreenshot{{ $i }}" title="{{ $title }}">
                                        
                                        </a>
                                    </div>
                                @endfor
                            </div> 
                        </div>
                    </div> <!-- /#screenshots-container .carousel .slide -->
                </div> <!-- /#screenshots-pane .tab-pane -->

                <div class="tab-pane" id="videos-pane"> 
                    <!-- <h4>{{ lang('common.videos') }}</h4> -->
                
                    <div id="videos-container">
                        <div id="videos-slider-wrapper" class="coda-slider-wrapper">
                            <div id="videos-slider" class="coda-slider">
                                <?php
                                $videos = $profile->videos;
                                ?>
                                @for ($i = 0; $i < count($videos['names']); $i++)
                                    <?php
                                    $video = new Video($videos['urls'][$i]);
                                    $title = $videos['names'][$i];
                                    ?>
                                    <div>
                                        <a href="{{ $video->embed_url }}" title="{{ $title }}" class="colorbox-videos-group">
                                            <img src="{{ $video->thumbnail_url }}" alt="{{ $title }}" title="{{ $title }}" id="gamevideo{{ $i }}">
                                        </a>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div> <!-- /#videos-pane .tab-pane -->

                <div class="tab-pane" id="soundtrack-pane">
                    <div id="soundtrack-container">
                        <?php
                        echo DisplaySoundtrack($profile->soundtrack);
                        ?>
                    </div>
                </div> <!-- /#soundtrack-pane .tab-pane -->
            </div> <!-- /.tab-content -->
        </div> <!-- /.span12 -->
    </div> <!-- /.row -->

    <?php
    $stores = $profile->stores;
    $stores_span = 12;

    $press = $profile->press;
    $press_count = count($press['names']);
    if ($press_count > 0) $stores_span -= 3;

    $social = $profile->socialnetworks;
    $social_count = count($social['names']);
    if ($social_count > 0) $stores_span -= 2;
    
    ?>
    <hr>

    <div class="row">
        <div class="span{{ $stores_span }}" id="stores-span">
            <h2>{{ lang('stores.title') }}</h2>

            @for ($i = 0; $i < count($stores['names']); $i++)
                <div class="store-icon">
                    <a href="{{ $stores['urls'][$i] }}">
                    <?php
                    $name = $stores['names'][$i];
                    $real_name = lang('stores.'.$name);
                    $icon_url = Config::get('vgc.stores_icons.'.$name);
                    ?>
                    @if ( ! is_null($icon_url))
                        <img src="{{ asset($icon_url) }}" alt="{{ $real_name }} icon" title="{{ lang('game.profile.get_this_game_from', array('store_name' => $real_name)) }}" /></a>
                    @else
                        {{ $real_name }}</a>
                    @endif
                </div> 
            @endfor
            
            <div class="clearfix"></div>
        </div> <!-- /.span8 -->

        @if ($press_count > 0)
            <div class="span3">
                <h3>{{ lang('press.title') }}</h3>

                <ul class="unstyled">
                    @for ($i = 0; $i < $press_count; $i++)
                        <li><a href="{{ $press['urls'][$i] }}">{{ $press['names'][$i] }}</a></li>
                    @endfor
                </ul>
            </div> <!-- /.span4 -->
        @endif

        @if ($social_count > 0)
            <div class="span2">
                <h4>{{ lang('socialnetworks.short_title') }}</h4>

                <ul class="unstyled">
                    @for ($i = 0; $i < $social_count; $i++)
                        <li><a href="{{ $social['urls'][$i] }}">{{ lang('socialnetworks.'.$social['names'][$i]) }}</a></li>
                    @endfor
                </ul>
            </div> <!-- /.span4 -->
        @endif
    </div> <!-- /.row -->

    <hr>

    <?php 
    $items = array('devices', 'operatingsystems', 'genres', 'looks', 'periods',
    'viewpoints', 'nbplayers', 'tags', 'languages', 'technologies' );
    $items_to_display = array();

    foreach ($items as $item) {
        $data = $profile->$item;
        
        if (is_array($data) && ! empty($data)) $items_to_display[] = array('name' => $item, 'data' => $data);
    }
    
    ?>


    <div class="row-fluid json-field-row">
        <?php

        for ($i = 0; $i <= 5; $i++):
            if ( ! isset($items_to_display[$i])) break;
            
            $item = $items_to_display[$i];
            unset($items_to_display[$i]);
        ?>
            <div class="span2 json-field-div">
                <h4>{{ lang($item['name'].'.title') }}</h4>

                <ul class="unstyled">
                    @foreach ($item['data'] as $name)
                        <li>{{ lang($item['name'].'.'.$name) }}</li>
                    @endforeach
                </ul>
            </div>
        @endfor 
    </div>

    @if (count($items_to_display) > 0)
        <hr>
     
        <div class="row-fluid json-field-row">
            <?php 
            $items_to_display = array_values($items_to_display);
            
            for ($i = 0; $i <= 50; $i++):
                if ( ! isset($items_to_display[$i])) break;

                $item = $items_to_display[$i];
                unset($items_to_display[$i]);
            ?>
                <div class="span2 json-field-div">
                    <h4>{{ lang($item['name'].'.title') }}</h4>

                    <ul class="unstyled">
                        @foreach ($item['data'] as $name)
                            <li>{{ lang($item['name'].'.'.$name) }}</li>
                        @endforeach
                    </ul>
                </div>
            @endfor
        </div>
    @endif

    @if ( ! $preview)
        <hr>

        <!-- Button to trigger modal -->
        <a class="muted accordion-toggle" data-toggle="collapse" href="#collapse-report">
            {{ icon('flag') }} {{ lang('common.report_profile_link') }}
        </a>
                
        <div id="collapse-report" class="collapse">
            <div class="accordion-inner">
                @include('forms/postreport')
            </div>
        </div>
    @endif
</div>

@section('cssfiles')
    {{ Asset::container('colorbox')->styles() }}
    {{ Asset::container('coda-slider')->styles() }}
@endsection

@section('jsfiles')
    {{ Asset::container('colorbox')->scripts() }}
    {{ Asset::container('coda-slider')->scripts() }}
@endsection

@section('jQuery')
    $('#medias-tabs a:first').tab('show');
    $('#stores-tabs a:first').tab('show');

    $('.screenshots-group').colorbox({rel:"group1"});
    $('#screenshots-slider').codaSlider({
        dynamicArrowsGraphical: true,
        dynamicTabs: false,
        autoSlide: true,
        autoHeightEaseDuration: 1000,
        autoSlideInterval: 1000,
    });


    $(".colorbox-videos-group").colorbox({iframe:true, innerWidth:800, innerHeight:533, rel:"group_video"});
    $('#videos-slider').codaSlider({
        dynamicArrowsGraphical: true,
        dynamicTabs: false,
        autoSlide: true,
        autoHeightEaseDuration: 1000,
        autoSlideInterval: 1000,
    });
@endsection
