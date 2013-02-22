<?php
if ( ! isset($preview)) $preview = false;

$name = $profile->name;
?>
@section('page_title')
    {{ $name }}
@endsection

<div id="tool-profile" class="profile">
    <?php
    $background_url = trim($profile->background);
    ?>
    @if ($background_url)
        <div id="profile-background">
            <img src="{{ $background_url }}" alt="Profile background">
        </div>
        <div id="profile-background-pusher"></div>
    @endif


    <div class="row-fluid">
        <div class="span6">
            <h3>{{ $name }} <small>{{ $profile->type }}
                @if ($preview)
                    preview
                @endif
            </small></h3> 
        </div>

        <div class="span4 header-side-column">
            <ul class="unstyled">
                <?php
                $website = $profile->website;
                $documentation = $profile->documentation;
                ?>
                @if ($website != '')
                    <li>{{ icon('globe', lang('common.website')) }} <a href="{{ $website }}" title="{{ lang('common.website') }}">{{ shortenUrl($website) }}</a></li>
                @endif

                @if ($documentation != '')
                    <li>{{ icon('cogs', lang('common.documentation')) }} <a href="{{ $documentation }}" title="{{ lang('common.documentation') }}">{{ lang('common.documentation') }}</a></li>
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
            </div> <!-- /.tab-content -->
        </div> <!-- /.span12 -->
    </div> <!-- /.row -->

    <hr>
    
    <div class="row-fluid json-field-row">
        <?php 
        $fields = array('socialnetworks',  );
        foreach ($fields as $field):
        ?>
            <div class="span6 json-field-div">
                @if ($field == 'socialnetworks' || $field == 'press')
                    <h4>{{ lang($field.'.title') }}</h4>
                @else
                    <h4>{{ lang($field.'.title') }} <small>{{ lang('vgc.'.$field.'.tool_help') }}</small></h4>
                @endif

                <ul class="unstyled">
                    @if ($field == 'socialnetworks')
                        <?php $array = $profile->$field; ?>

                        @for ($i = 0; $i < count($array['names']); $i++)
                            <li><a href="{{ $array['urls'][$i] }}">{{ lang($field.'.'.$array['names'][$i]) }}</a></li>
                        @endfor

                    @elseif ($field == 'press')
                        <?php $array = $profile->$field; ?>

                        @for ($i = 0; $i < count($array['names']); $i++)
                            <li><a href="{{ $array['urls'][$i] }}">{{ $array['names'][$i] }}</a></li>
                        @endfor

                    @elseif ($field == 'tool_works_on_os')
                        @foreach ($profile->$field as $name)
                            <li>{{ lang('operatingsystems.'.$name) }}</li>
                        @endforeach

                    @else
                        @foreach ($profile->$field as $name)
                            <li>{{ lang($field.'.'.$name) }}</li>
                        @endforeach
                    @endif
                </ul>
            </div>
        @endforeach 
    
    </div>

    <hr>

    <div class="row-fluid json-field-row">
        <?php 
        $fields = array('tool_works_on_os', 'scriptinglanguages', 'operatingsystems', 'devices',  );
        foreach ($fields as $field):
        ?>
            <div class="span3 json-field-div">
                @if ($field == 'socialnetworks' || $field == 'press')
                    <h4>{{ lang($field.'.title') }}</h4>
                @else
                    <h4>{{ lang($field.'.title') }} <small>{{ lang('vgc.'.$field.'.tool_help') }}</small></h4>
                @endif

                <ul class="unstyled">
                    @if ($field == 'socialnetworks')
                        <?php $array = $profile->$field; ?>

                        @for ($i = 0; $i < count($array['names']); $i++)
                            <li><a href="{{ $array['urls'][$i] }}">{{ lang($field.'.'.$array['names'][$i]) }}</a></li>
                        @endfor

                    @elseif ($field == 'press')
                        <?php $array = $profile->$field; ?>

                        @for ($i = 0; $i < count($array['names']); $i++)
                            <li><a href="{{ $array['urls'][$i] }}">{{ $array['names'][$i] }}</a></li>
                        @endfor

                    @elseif ($field == 'tool_works_on_os')
                        @foreach ($profile->$field as $name)
                            <li>{{ lang('operatingsystems.'.$name) }}</li>
                        @endforeach

                    @else
                        @foreach ($profile->$field as $name)
                            <li>{{ lang($field.'.'.$name) }}</li>
                        @endforeach
                    @endif
                </ul>
            </div>
        @endforeach 
    
    </div>

    @if (false && ! $preview)
        <hr>

        <h4>{{ lang('common.games') }}</h4>
            
        <div class="row-fluid">
            <div class="span12">
                <?php
                $profiles = $profile->games;
                ?>
                @if (empty($profiles))
                    <p>
                        {{ lang('developer.profile.no_game') }}
                    </p>
                @else
                    @include('partials.profile_list_tiles')
                @endif
            </div>
        </div>

        <hr>

        <a class="muted accordion-toggle" data-toggle="collapse" href="#collapse-report">
            {{ icon('flag') }} {{ lang('common.report_profile_link') }}
        </a>
        
        <div id="collapse-report" class="collapse">
            @include('forms/postreport')
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

