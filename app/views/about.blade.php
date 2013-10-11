<div id="about">
    <h1>{{ lang('about.title') }}</h1>

    <hr>

    <p id="about_slogan">
        {{ lang('home.site_slogan') }} {{ lang('home.site_slogan2') }}
    </p>

    <hr>

    <p>
        {{ lang('about.text') }}
    </p>

    <br>

    <ul class="nav nav-tabs" id="main-tabs">
        <li><a href="#contact-pane" data-toggle="tab">{{ lang('about.contact_title') }}</a></li>
        <li><a href="#developement-pane" data-toggle="tab">{{ lang('about.developement_title') }}</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane" id="developement-pane"> 
            <h3>{{ lang('about.developement_title') }}</h3>

            <p>
                {{ lang('about.developement_text') }}
            </p>
        </div>

        <div class="tab-pane" id="contact-pane"> 
            <h3>{{ lang('about.contact_title') }}</h3>

            <ul>
                <li><a href="mailto:contact@videogameschest.com">contact@videogameschest.com</a></li>
                <li><a href="{{ Config::get('vgc.social.twitter_url') }}" title="Twitter">@VideoGamesChest</a></li>
                <li><a href="{{ Config::get('vgc.social.google+_url') }}" title="Google+">Google+ page</a></li>
                <li><a href="{{ Config::get('vgc.social.facebook_url') }}" title="Facebook">Facebook page</a></li>
            </ul>
        </div>
    </div>
</div>

@section('jQuery')
// from about
$('#main-tabs a:first').tab('show');
@endsection