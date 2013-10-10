@section('page_title')
    {{ $profile->name }}
@endsection

<div id="game" class="profile-page mini-profile">
    <div class="row-fluid">
        <?php
        $screenshots = $profile->screenshots;
        //var_dump($screenshots);
        ?>
        @if ( ! empty($screenshots))
            <div class="span4">
                <img src="{{ $screenshots[0]['url'] }}" alt="{{ $screenshots[0]['name'] }}" >
            </div>
        @endif

        <div id="medias-container">
            <?php
            $colorbox_group = "colorbox_group_".Str::random(20);
            ?>
            @foreach ($screenshots as $screenshot)
                <a href="{{ $screenshot['url'] }}" title="{{ $screenshot['name'] }}" rel="{{ $colorbox_group }}">
                    <img src="{{ $screenshot['url'] }}" alt="{{ $screenshot['name'] }}">
                </a>
            @endforeach


        </div>

        <!-- text column -->
        <div class="span8">
            <div class="row-fluid">
                <div class="span12">
                    <h4>
                        @if ( ! empty($profile->links))
                            <a href="$profile->links[0]['url']" title="{{ $profile->name }}">{{ $profile->name }}</a>
                        @else
                            {{ $profile->name }}
                        @endif
                    </h4>
                </div>
            </div> <!-- / name row-->

            <div class="row-fluid">
                <div class="span12">
                    <blockquote id="pitch">
                        <p>
                            {{ substr($profile->pitch, 0, Config::get("vgc.profile_pitch_length"))." ..." }}
                        </p>
                    </blockquote>
                </div>

            </div> <!-- /pitch row -->
            
            

            <!-- more links row -->
            <div class="row-fluid">
                <div class="span2">
                    <!-- more info btn -->
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            {{ lang('vgc.profile.more_infos') }}
                        </a>
                        <ul class="dropdown-menu">
                            @if ($profile->developer_name != '' && $profile->developer_url != '')
                                <li>{{ lang('vgc.common.developer') }} : <a href="{{ $profile->developer_url }}" title="{{ $profile->developer_name }}">{{ $profile->developer_name }}</a></li>
                            @elseif ($profile->developer_name != '' && $profile->developer_url == '')
                                <li>{{ lang('vgc.common.developer') }} : {{ $profile->developer_name }}</li>
                            @elseif ($profile->developer_name == '' && $profile->developer_url != '')
                                <li><a href="{{ $profile->developer_url }}" title="{{ lang('vgc.common.developer') }}">{{ lang('vgc.common.developer') }}</a></li>
                            @endif

                            @if ($profile->price != '')
                                <li>{{ lang('vgc.common.price') }} : 

                                @if ((float)$profile->price == 0.0)
                                    {{ lang('vgc.common.price_free') }}
                                @else
                                    {{ $profile->price }}
                                @endif
                                $
                            @endif

                            @if ($profile->release_date != '' && $profile->release_date != '0000-00-00 00:00:00')
                                <li>{{ lang('vgc.common.release_date') }} : {{ date_create($profile->release_date)->format(Config::get('vgc.date_formats.blog')) }}</li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="span2">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            {{ lang('vgc.profile.more_links') }}
                        </a>
                        <ul class="dropdown-menu">
                            @foreach($profile->links as $link)
                                <li><a href="{{ $link['url'] }}" title="{{ $link['name'] }}">{{ $link['name'] }}</a></li>
                            @endforeach
                        </ul>   
                    </div>
                </div> <!-- span 12 -->

                <div class="span2">
                    <div class="dropdown">
                        <a href="#" id="{{ $colorbox_group }}">
                            {{ lang('vgc.profile.more_medias') }}
                        </a> 
                    </div>
                </div> <!-- span 12 -->
            </div> <!-- /row more -->
        </div> <!-- /text column -->
    </div>
</div>

@section('jQuery')
    $('#{{ $colorbox_group }}').click(function() {
        $('a[rel={{ $colorbox_group }}]').colorbox({ rel:'{{ $colorbox_group }}', open:true });
     });
@endsection