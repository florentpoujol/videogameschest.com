@section('page_title')
    {{ $profile->name }} | {{ lang('developer.profile.title') }}
@endsection

<div id="developer-profile" class="profile">
    <div class="row">
        <div class="span4">
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

    <div class="row json-item-row">
        
        <?php 
        $items = array('socialnetworks', 'stores', 'devices', 'operatingsystems', 'technologies');
        foreach ($items as $item):
        ?>
            <div class="span2 json-item-div">
                <h4>{{ lang($item.'.title') }}</h4>

                <ul class="unstyled">
                    @if ($item == 'socialnetworks')
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

    <!-- Button to trigger modal -->
    <a href="#report_modal" data-toggle="modal" class="muted">Report this profile</a>
</div>

 
<!-- Modal -->
<div id="report_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <?php $modal = true; ?>
   @include('report')
</div>

