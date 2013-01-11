@section('page_title')
    {{ $profile->name }}
@endsection

<div id="developer-profile" class="profile">
    <div class="row">
        <div class="span5">
            <h3>{{ $profile->name }} <small>{{ $profile->class_name }}</small></h3> 
            
        </div>

        <div class="span4 align-center">
            <ul class="unstyled">
                <li><a href="{{ $profile->website }}" class="">{{ $profile->website }}</a></li>
                <li><a href="mailto:{{ $profile->email }}" class="">{{ $profile->email }}</a></li>
            </ul>
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
            
            {{ $profile->get_parsed_pitch() }}
        </div>
    </div>

    <hr>

    <div class="row json-field-row">
        
        <?php 
        $fields = array('socialnetworks', 'stores', 'devices', 'operatingsystems', 'technologies');
        foreach ($fields as $field):
        ?>
            <div class="span2 json-field-div">
                <h4>{{ lang($field.'.title') }}</h4>

                <ul class="unstyled">
                    @if ($field == 'socialnetworks')
                        <?php $array = $profile->$field; ?>

                        @for ($i = 0; $i < count($array['names']); $i++)
                            <li>{{ icon($array['names'][$i]) }}<a href="{{ xss_secure($array['urls'][$i]) }}">{{ lang($field.'.'.$array['names'][$i]) }}</a></li>
                        @endfor
                    @else
                        @foreach ($profile->$field as $name)
                            <li>{{ icon($name) }}{{ lang($field.'.'.$name) }}</li>
                        @endforeach
                    @endif
                </ul>
            </div>
        @endforeach 
    
    </div>

    <hr>

    <div class="row">
        <div class="span12">
            <h3>{{ lang('common.games') }}</h3>
        </div>
    </div>

    <div class="row">
        <div class="span12">
            <?php
            $profiles = $profile->games;
            ?>
            @if (empty($profiles))
                <p>No games</p>
            @else
                @include('partials.profile_list')
            @endif
        </div>
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

