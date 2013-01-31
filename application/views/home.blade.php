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
            <p>
                {{ lang('home.home_text') }}
            </p>

            <a href="{{ route('get_register') }}" class="btn btn-primary">{{ lang('common.learn_more') }}</a>
        </div>

        <div class="span6">
            <h2>{{ lang('search.title') }} <small>{{ lang('search.subtitle') }}</small></h2>
            
            <p>
                {{ lang('search.home_text') }}
            </p>

            <a href="{{ route('get_search') }}" class="btn btn-primary">{{ lang('common.learn_more') }}</a>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="span6">
            <h2>{{ lang('discover.title') }} <small>{{ lang('discover.subtitle') }}</small></h2>

            <p>
                <?php
                echo lang('discover.home_text', array(
                    'search_link' => route('get_search'),
                ));
                ?>
            </p>

            <a href="{{ route('get_discover_page') }}" class="btn btn-primary">{{ lang('common.learn_more') }}</a>
        </div>

        <div class="span6">
            <h2>{{ lang('promotion.title') }} <small>{{ lang('promotion.subtitle') }}</small></h2>

            <p>
                {{ lang('promotion.home_text') }}
            </p>

            <a href="{{ route('get_promotion') }}" class="btn btn-primary btn-learn-more">{{ lang('common.learn_more') }}</a>
        </div>
    </div>

</div> <!-- /#home -->
