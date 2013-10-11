@section('page_title')
    {{ $profile->name }}
@endsection

<div id="game" class="profile-page mini-profile">
    <div class="row-fluid">
        <?php
        $medias = $profile->medias;
        // dd($medias);
        ?>
        @if ( ! empty($medias))
            <div class="span4">
                <img src="{{ $medias[0]['url'] }}" alt="{{ $medias[0]['name'] }}" >
            </div>
        @endif

        <div id="medias-container">
            <?php
            $colorbox_group = "colorbox_group_".Str::random(20);
            ?>
            @foreach ($medias as $media)
                <a href="{{ $media['url'] }}" title="{{ $media['name'] }}" rel="{{ $colorbox_group }}">
                    <img src="{{ $media['url'] }}" alt="{{ $media['name'] }}">
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
                            {{ substr($profile->description, 0, Config::get("vgc.profile_pitch_length"))." ..." }}
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