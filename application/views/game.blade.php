@section('page_title')
    {{ $profile->name }}
@endsection

<div id="game" class="profile-page mini-profile">
    <div class="row">
        <?php
        $screenshots = $profile->screenshots;
        var_dump($screenshots);
        ?>
        <div class="span4">
            <img src="{{ $screenshots['urls'][0] }}" alt="{{ $screenshots['names'][0] }}" >
        </div>

        <div id="medias-container">
            @for ($i = 0; $i < count($screeshots['names']); $i++)
                <a href="{{ $screenshots['urls'][$i] }}" title="{{ $screenshots['names'][$i] }}">
                    <img src="{{ $screenshots['urls'][$i] }}" alt="{{ $screenshots['names'][$i] }}">
                </a>
            @endfor
        </div>

        <div class="span8">
            <div class="row navbar">
                <ul class="nav">
                    <li class="name">{{ $profile->name }}</li>

                    <li class="more">
                        <ul class="unstyled">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    {{ lang('vgc.profile.more_infos') }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li>bla</li>
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
                                    <li>bli</li>
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