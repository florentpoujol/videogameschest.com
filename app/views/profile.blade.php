@section('page_title')
    {{ $profile->name }}
@endsection

<div id="game" class="profile-page mini-profile">
    
    <?php
    $medias = $profile->medias;
    // dd($medias);
    ?><!-- 
    @if ( ! empty($medias))
        <div class="span4">
            <img src="{{ $medias[0]['url'] }}" alt="{{ $medias[0]['name'] }}" >
        </div>
    @endif
-->
    <!-- <div id="medias-container">
        <?php
        $colorbox_group = "colorbox_group_".Str::random(20);
        ?>
        @foreach ($medias as $media)
            <a href="{{ $media['url'] }}" title="{{ $media['name'] }}" rel="{{ $colorbox_group }}">
                <img src="{{ $media['url'] }}" alt="{{ $media['name'] }}">
            </a>
        @endforeach
    </div> -->

    <!-- text column -->
        
    <div class="row">
        <div class="span12">
            <h4>
                {{ $profile->name }}
                <small>
                
                </small>
            </h4>
        </div>
    </div> <!-- / name row-->

    <div class="row">
        <div class="span12">
            
                <p>
                    {{ substr($profile->description, 0, Config::get("vgc.profile_pitch_length"))." ..." }}
                    
                    @if ($profile->release_date != '' && $profile->release_date != '0000-00-00')
                        <br>
                        {{ lang('vgc.common.release_date') }} : {{ date_create($profile->release_date)->format(Config::get('vgc.date_formats.blog')) }}
                    @endif
                </p>
            
        </div>

    </div> <!-- /pitch row -->
            
            

    <!-- more links row -->
    <div class="row" id="link-media-row">
        <div class="span3">
            <h4>Links</h4>

            <div class="dropdown">
                <?php
                $links = $profile->links;

                if ( count($links) > 0 ):
                    $links = array_chunk( $profile->links, Config::get('vgc.profile_max_link_count') );
                    if ( count($links) > 2 ) {
                        for ( $i = 2; $i < count($links); $i++ )
                            $links[1] = array_merge( $links[1], $links[$i] );
                    }
                ?>
                    <ul>
                        @foreach($links[0] as $link)
                            <li><a href="{{ $link['url'] }}">{{ $link['name'] }}</a></li>
                        @endforeach
                    </ul>
                    
                    @if ( isset( $links[1] ) )
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            {{ lang('vgc.profile.more_links') }}
                        </a>
                        <ul class="dropdown-menu">
                            @foreach($links[1] as $link)
                                <li><a href="{{ $link['url'] }}" title="{{ $link['name'] }}">{{ $link['name'] }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                @endif
            </div>
        </div> <!-- span 12 -->

        <div class="span2">
            <h4>Medias</h4>
            <div class="dropdown">
                <a href="#" id="{{ $colorbox_group }}">
                    {{ lang('vgc.profile.more_medias') }}
                </a>
            </div>
        </div> <!-- span 12 -->
    </div> <!-- /row likns medias -->

    <div class="row">
        <div class="span12">
            <h4>Tags :</h4>
            <p>
            @foreach( $profile->tags as $tag)
                {{ $tag->name }},
            @endforeach
            </p>
        </div>
    </div>
    
</div>

@section('jQuery')
    $('#{{ $colorbox_group }}').click(function() {
        $('a[rel={{ $colorbox_group }}]').colorbox({ rel:'{{ $colorbox_group }}', open:true });
     });
@endsection