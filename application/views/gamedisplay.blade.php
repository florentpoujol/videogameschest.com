@section('page_title')
    {{ $profile->name }}
@endsection
<?php

?>
<div id="game-profile" class="profile">
    <div class="row">
        <div class="span9 pitch-block">
            <div class="row">
                <div class="span5">
                    <h3>{{ $profile->name }} <small>{{ $profile->class_name }}</small></h3> 
                </div>
                <div class="span4 website align-center">
                    <a href="{{ $profile->website }}" class="">{{ $profile->website }}</a>
                </div>
            </div>

            <hr>

            <p>
                {{ $profile->get_parsed_pitch() }}
            </p>
        </div>

        <div class="span3">
            <img src="{{ $profile->cover }}" alt="{{ $profile->name }} cover" class="logo" >

            <ul class="unstyled">
                <li>{{ lang('developmentstates.title') }} : {{ substr(lang('developmentstates.'.$profile->devstate), 4) }}</li>

                @if ($profile->soundtrackurl != '')
                    <li><a href="{{ $profile->soundtrackurl }}">{{ lang('game.profile.soundtrack') }}</a></li>
                @endif

                @if ($profile->publishername != '')
                    <li>{{ lang('common.publisher') }} : <a href="{{ $profile->publisherurl }}" title="{{ $profile->publishername }}" class="">{{ $profile->publishername }}</a></li>
                @endif
            </ul>

            @if ($profile->blogfeed != '')
                <h4>{{ lang('game.profile.blogfeed') }}</h4>

                <ul class="unstyled">
                    <li>bla</li>
                </ul>
            @endif
        </div>
    </div>

    <hr>

    <div class="row json-item-row">
        
        <h4>{{ lang('common.screenshots') }}</h4>

        <div id="screenshots-container">
            <?php
            $screenshots = $profile->screenshots;
            ?>
            @for ($i = 0; $i < count($screenshots['names']); $i++)
                <a href="{{ $screenshots['urls'][$i] }}" title="{{ $screenshots['names'][$i] }}" class="colorbox-group1">
                    <img src="{{ $screenshots['urls'][$i] }}" alt="{{ $screenshots['names'][$i] }}">
                </a> 
            @endfor
        </div> 
    </div>

    <hr>

    <div class="row json-item-row">
        
        <h4>{{ lang('common.videos') }}</h4>
        
        <div id="videos-container">
            <?php
            $videos = $profile->videos;
            ?>
            @for ($i = 0; $i < count($videos['names']); $i++)
                
            @endfor
        </div> 
    </div>

    <hr>

    <div class="row json-item-row json-item-row-6">
        
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
    <a href="#report_modal" data-toggle="modal" class="muted">Report this profile</a>
</div>

 
<!-- Modal -->
<div id="report_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <?php $modal = true; ?>
   @include('report')
</div>

@section('cssfiles')
    {{ HTML::style('css/colorbox.css') }}
@endsection
@section('jsfiles')
    {{ HTML::script('js/jquery.colorbox-min.js') }}
@endsection

@section('jQuery')
    $(".colorbox-group1").colorbox({rel:"group1"});
@endsection

