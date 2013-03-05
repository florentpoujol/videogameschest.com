@section('page_title')
    {{ lang('vgc.home.site_slogan') }}
@endsection

<div id="home">

    <hr>

    <div class="row">
        <div class="span12">
            <div id="slogan">
               {{ lang('vgc.home.site_slogan') }}</p>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="span12" id="catchphrase">
            {{ lang('vgc.home.catchphrase') }}
        </div>
    </div>

    <hr>

    <!-- search -->
    <div class="row">
        <div class="span12">
            <div class="pull-right muted home-icons">
                {{ icon('search') }}
            </div>

            <h3>{{ lang('vgc.search.title') }}</h3>

            <p>
                {{ lang('vgc.search.home_text') }}
            </p>
        </div>
    </div>

    <hr>

    <!-- discover -->
    <div class="row">
        <div class="span12">
            <div class="text-align-right">        
                <h3>{{ lang('vgc.discover.title') }}</h3>

                <p>
                    {{ lang('vgc.discover.home_text') }}
                </p>
            </div>

            <div class="muted home-icons">
                {{ icon('eye-open') }}
            </div>
        </div>
    </div>

    <hr>

    <!-- promote -->
    <div class="row">
        <div class="span6">
            <h3>{{ lang('vgc.promote.title') }}</h3>

            <p>
                {{ lang('vgc.promote.home_text') }}
            </p>
        </div>

        <div class="span6">
            <h3>{{ lang('vgc.crosspromotion.title') }}</h3>

            <p>
                {{ lang('vgc.crosspromotion.home_text') }}
            </p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="span12" id="">
            <h3>{{ lang('about.developement_title') }}</h3>

            <p>
                {{ lang('about.developement_text', array('blog_url' => route('get_blog_page'))) }}
            </p>
        </div>
    </div>

</div> <!-- /#home -->
