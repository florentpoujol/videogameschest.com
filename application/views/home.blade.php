@section('page_title')
    {{ lang('common.site_url') }} - {{ lang('home.site_slogan_full') }}
@endsection

<div id="home">

    <hr>

    <div class="row">
        <div class="span12">
            <div id="slogan">
                <p id="slogan-left">{{ lang('home.site_slogan') }}</p>
                <p id="slogan-right" class="pull-right">{{ lang('home.site_slogan2') }}</p>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="span6">
            <h2>{{ lang('participate.title') }}</h2>

            <p>
                {{ lang('participate.home_text') }}
            </p>

            <a href="{{ route('get_participate_page') }}" class="btn btn-primary">{{ lang('common.learn_more') }} {{ icon('double-angle-right') }}</a>
        </div>

        <div class="span6">
            <h2>{{ lang('search.title') }}</h2>
            
            <p>
                {{ lang('search.home_text') }}
            </p>

            <a href="{{ route('get_search_page') }}" class="btn btn-primary">{{ lang('common.learn_more') }} {{ icon('double-angle-right') }}</a>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="span6">
            <h2>{{ lang('discover.title') }}</h2>

            <p>
                <?php
                echo lang('discover.home_text', array(
                    'search_link' => route('get_search_page'),
                ));
                ?>
            </p>

            <a href="{{ route('get_discover_page') }}" class="btn btn-primary">{{ lang('common.learn_more') }} {{ icon('double-angle-right') }}</a>
        </div>

        <div class="span6">
            <h2>{{ lang('promote.title') }}</h2>

            <p>
                {{ lang('promote.home_text') }}
            </p>

            <a href="{{ route('get_promote_page') }}" class="btn btn-primary btn-learn-more">{{ lang('common.learn_more') }} {{ icon('double-angle-right') }}</a>
        </div>
    </div>

</div> <!-- /#home -->
