@section('page_title')
    {{ $profile->name }}
@endsection

<div id="game" class="profile-page mini-profile">
    <div class="row">
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
            @foreach ($screenshots as $screenshot)
                <a href="{{ $screenshot['url'] }}" title="{{ $screenshot['name'] }}">
                    <img src="{{ $screenshot['url'] }}" alt="{{ $screenshot['name'] }}">
                </a>
            @endforeach
        </div>

        <div class="span8">
            <div class="row navbar">
                <ul class="nav">
                    <li class="name">
                        @if ( ! empty($profile->links))
                            <a href="$profile->links[0]['url']" title="{{ $profile->name }}">{{ $profile->name }}</a>
                        @else
                            {{ $profile->name }}
                        @endif
                    </li>

                    <li class="more">
                        <ul class="unstyled">
                            <li class="dropdown">
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

                                    @if ($profile->release_date != '')
                                        <li>{{ lang('vgc.common.release_date') }} : {{ date_create($profile->release_date)->format(Config::get('vgc.date_formats.blog')) }}</li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="more">
                        <ul class="unstyled">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    {{ lang('vgc.profile.more_links') }}
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach($profile->links as $link)
                                        <li><a href="{{ $link['url'] }}" title="{{ $link['name'] }}">{{ $link['name'] }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="more">
                        <a href="http://blabla" >
                            {{ lang('vgc.profile.more_medias') }}
                        </a>
                    </li>
                </ul>
            </div> <!-- / name row-->

            <div class="row">
                <div class="span8">
                    {{ $profile->pitch }}
                </div>
            </div> <!-- pitch row -->
        </div>
    </div>
</div>

@section('jQurey')
    
@endsection