@section('page_title')
    {{ $profile->name }} | {{ lang('game.profile.title') }}
@endsection
<?php

?>
<div id="game_profile" class="profile game-profile">
    <div class="row">
        <div class="span4">
            <h3>{{ $profile->name }} <small>{{ $profile->class_name }}</small></h3> 
            
        </div>

        <div class="span4 align-center website">
            <a href="{{ $profile->website }}" class="">{{ $profile->website }}</a>
        </div>

        <div class="span3 align-center">
            <ul class="unstyled">
                <li>{{ lang('common.country') }} : {{ Str::title($profile->country) }}</li>
                <li>{{ lang('common.teamsize') }} : {{ $profile->teamsize }} <i class="icon-user" title="teamsize"></i></li>
            </ul>
        </div>
    </div>

    <hr>

    <div class="row" >
        <div class="span12 pitch">
            <img src="{{ $profile->logo }}" alt="{{ $profile->name }} logo" class="logo pull-right" >
            
            {{ parse_bbcode($profile->pitch) }}
        </div>
    </div>

    <hr>

    <div class="row json-item-row">
        
        <?php 
        $items = array('socialnetworks', 'stores','operatingsystems', 'devices', 'technologies', 'genres');
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

    <div class="row json-item-row">
        
        <?php 
        $items = array('themes', 'viewpoints', 'nbplayers', 'tags', 'languages');
        foreach ($items as $item):
        ?>
            <div class="span2 json-item-div">
                <h4>{{ lang($item.'.title') }}</h4>

                <ul class="unstyled">
                    
                        @foreach ($profile->$item as $name)
                            <li>{{ icon($name) }}{{ lang($item.'.'.$name) }}</li>
                        @endforeach
                    
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

