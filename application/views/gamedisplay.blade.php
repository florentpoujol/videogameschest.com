@section('page_title')
    {{ $profile->name }}
@endsection
<?php

?>
<div id="game-profile" class="profile">
    <div class="row-fluid">
        <div class="span4">
            <h3>{{ $profile->name }} <small>{{ $profile->class_name }}</small></h3> 
        </div>

        <div class="span3 align-center">
            <ul class="unstyled">
                @if ($profile->publishername != '')
                    <li>{{ lang('common.publisher') }} : <a href="{{ $profile->publisherurl }}" title="{{ $profile->publishername }}" class="">{{ $profile->publishername }}</a></li>
                @endif

                <li>{{ lang('common.developer') }} : <a href="{{ route('get_developer', array(name_to_url($profile->dev->name))) }}" title="{{ $profile->dev->name }}" class="">{{ $profile->dev->name }}</a></li>
            </ul>
        </div>

        <div class="span3 align-center">
            <ul class="unstyled">
                <a href="{{ $profile->website }}" class="">{{ lang('game.profile.website') }}</a>
                @if ($profile->soundtrackurl != '')
                    <li><a href="{{ $profile->soundtrackurl }}">{{ lang('game.profile.soundtrack') }}</a></li>
                @endif
            </ul>
        </div>

        <div class="span2">
            <img src="{{ $profile->cover }}" alt="{{ $profile->name }} cover" id="game-cover" >
        </div>
    </div>

    <hr>

    <div class="row-fluid">
            <?php 
            if ($profile->blogfeed == '') $span = '12';
            else $span = '9';
            ?>
            <div class="span{{ $span }}">
                {{ $profile->get_parsed_pitch() }}
            </div>

            @if ($profile->blogfeed != '')
                <div class="span3">
                    <h4>{{ lang('game.profile.blogfeed') }}</h4>

                    <ul class="unstyled">
                        <?php
                        $feed = RSSReader::read($profile->blogfeed, Config::get('vgc.game_feed_item_count'));
                        ?>

                        @foreach ($feed['items'] as $item)
                            <li><a href="{{ $item['link'] }}">{{ $item['title'] }}</a></li>
                        @endforeach
                        
                        
                    </ul>
                </div>
            @endif
    </div>

    <hr>

    <div class="row-fluid">
        <div class="span12">
            <h4>{{ lang('common.screenshots') }}</h4>

            <div id="screenshots-container">
                <?php
                $screenshots = $profile->screenshots;
                ?>
                @for ($i = 0; $i < count($screenshots['names']); $i++)
                    <a href="{{ $screenshots['urls'][$i] }}" title="{{ $screenshots['names'][$i] }}" class="colorbox-group1">
                        <img src="{{ $screenshots['urls'][$i] }}" alt="{{ $screenshots['names'][$i] }}" id="utyhg_{{ $i }}">
                    </a> 
                @endfor
            </div> 
        </div>
    </div>

    <hr>

    <div class="row-fluid">
        <div class="span12">
            <h4>{{ lang('common.videos') }}</h4>
        
            <div id="videos-container">
                <?php
                $videos = $profile->videos;
                ?>
                @for ($i = 0; $i < count($videos['names']); $i++)
                    {{ video_frame($videos['urls'][$i]) }}
                @endfor
            </div>
        </div>
    </div>

    <hr>

    <div class="row-fluid json-item-row json-item-row-6">
        
        <?php 
        $items = array('socialnetworks', 'stores', 'devices', 'operatingsystems',  'genres','themes',);
        foreach ($items as $item):
        ?>
            <div class="span2 json-item-div">
                <h4>{{ lang($item.'.title') }}</h4>

                <ul class="unstyled">
                    @if ($item == 'socialnetworks' || $item == 'stores')
                        <?php $array = $profile->$item; ?>

                        @for ($i = 0; $i < count($array['names']); $i++)
                            <li>{{ icon($array['names'][$i]) }}<a href="{{ $array['urls'][$i] }}">{{ lang($item.'.'.$array['names'][$i]) }}</a></li>
                        @endfor
                    @else
                        @foreach ($profile->$item as $name)
                            <li>{{ icon($name) }}{{ lang($item.'.'.$name) }}</li>
                        @endforeach
                    @endif
                </ul>
            </div>
        @endforeach 
    
    </div>

    <hr>

    <div class="row json-item-row json-item-row-5">
        
        <?php 
        $items = array( 'viewpoints', 'nbplayers', 'tags', 'languages', 'technologies');
        foreach ($items as $item):
        ?>
            <div class="span2 json-item-div">
                <h4>{{ lang($item.'.title') }}</h4>

                <ul class="unstyled">
                    @if (is_array($profile->$item))
                        @foreach ($profile->$item as $name)
                            <li>{{ icon($name) }}{{ lang($item.'.'.$name) }}</li>
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
            @include('report_form')
        </div>
    </div>
</div>

@section('cssfiles')
    {{ HTML::style('css/colorbox.css') }}
    {{ HTML::style('css/smoothDivScroll.css') }}
@endsection

@section('jsfiles')
    {{ HTML::script('js/jquery.colorbox-min.js') }}
    <!-- smoothDivScroll js files -->
    {{ Asset::container('smoothDivScroll')->scripts() }}
    <!-- /smoothDivScroll -->
@endsection

@section('jQuery')
    $(".colorbox-group1").colorbox({rel:"group1"});

    $("#screenshots-container").smoothDivScroll({
        manualContinuousScrolling: false,
        autoScrollingMode: "onStart",
    });

    $("#videos-container").smoothDivScroll({
        manualContinuousScrolling: false,
        autoScrollingMode: "onStart",
    });
@endsection

