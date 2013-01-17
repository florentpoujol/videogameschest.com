<?php
$name = xssSecure($profile->name);
?>
@section('page_title')
    {{ $name }}
@endsection

<div id="developer-profile" class="profile">
    <div class="row-fluid">
        <div class="span6">
            <h3>{{ $name }} <small>{{ $profile->class_name }}</small></h3> 
        </div>

        <div class="span4 header-side-column">
            <ul class="unstyled">
                <?php
                $website = trim(xssSecure($profile->website));
                $email = trim(xssSecure($profile->email));
                $presskit = trim(xssSecure($profile->presskit));
                ?>
                @if ($website != '')
                    <li>{{ icon('globe', lang('common.website')) }}<a href="{{ $website }}" title="{{ lang('common.website') }}">{{ shortenUrl($website) }}</a></li>
                @endif

                @if ($email != '')
                    <li>{{ icon('envelope-alt', lang('common.email')) }}<a href="mailto:{{ $email }}" title="{{ lang('common.email') }}">{{ $email }}</a></li>
                @endif

                @if ($presskit != '')
                    <li>{{ icon('folder-open', lang('common.presskit')) }}<a href="{{ $presskit }}" title="{{ lang('common.presskit') }}">{{ shortenUrl($presskit) }}</a></li>
                @endif
            </ul>
        </div>

        <div class="span2 header-side-column">
            <ul class="unstyled">
                <li>{{ icon('flag', lang('common.country')) }}{{ Str::title(xssSecure($profile->country)) }}</li>
                <li>{{ icon('group', lang('common.teamsize')) }}{{ xssSecure($profile->teamsize) }}</li>
            </ul>
        </div>
    </div>

    <hr>

    <div class="row-fluid" >
        <div class="span12">
            <?php
            $blogfeed = xssSecure($profile->blogfeed);
            ?>
            @if ($blogfeed != '')
                <div class="span4">
                    <h4>
                        {{ lang('common.profile_blogfeed') }}
                        <a href="{{ $blogfeed }}" title="Blog feed">{{ icon('rss') }}</a>
                    </h4>

                    <ul class="unstyled">
                        <?php
                        $feed = RSSReader::read($blogfeed, Config::get('vgc.dev_feed_item_count'));
                        ?>

                        @foreach ($feed['items'] as $item)
                            <li><a href="{{ $item['link'] }}" title="{{ $item['title'] }}">{{ $item['title'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <img src="{{ xssSecure($profile->logo) }}" alt="{{ $name }} logo" title="{{ $name }} logo" class="logo pull-right">

            <i class="icon-quote-left icon-3x pull-left icon-muted"></i>

            {{ $profile->get_parsed_pitch() }}

            <i class="icon-quote-right icon-3x pull-right icon-muted"></i>
        </div>
    </div>

    <hr>
    <div class="row-fluid">
    
    </div>
    <div class="row-fluid json-field-row">
        <?php 
        $fields = array('socialnetworks', 'stores', 'devices', 'operatingsystems', 'technologies');
        foreach ($fields as $field):
        ?>
            <div class="span3 json-field-div">
                <h4>{{ lang($field.'.title') }}</h4>

                <ul class="unstyled">
                    @if ($field == 'socialnetworks')
                        <?php $array = $profile->$field; ?>

                        @for ($i = 0; $i < count($array['names']); $i++)
                            <li><a href="{{ xssSecure($array['urls'][$i]) }}">{{ lang($field.'.'.$array['names'][$i]) }}</a></li>
                        @endfor
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
        {{ lang('common.report_profile_link') }}
    </a>
    
    <div id="collapse-report" class="collapse">
        @include('report_form')
    </div>
</div>

 

