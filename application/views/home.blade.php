@section('page_title')
    {{ lang('home.title') }}
@endsection

<div id="home">
    <div class="row">
        <div class="span1"></div>
        <div class="span5">
            <h2>{{ lang('home.participative.title') }}</h2>

            <p>
                {{ lang('home.participative.text') }}
            </p>

            <br>

            <div class="align-center">
                <a href="{{ route('get_adddeveloper') }}" title="{{ lang('home.participative.adddev_link') }}" class="btn btn-primary btn-large align-center">{{ lang('home.participative.adddev_link') }}</a>  
                <a href="{{ route('get_addgame') }}" title="{{ lang('home.participative.addgame_link') }}" class="btn btn-primary btn-large align-center">{{ lang('home.participative.addgame_link') }}</a>
            </div>

            <br>
 
            <a title="{{ lang('home.participative.submission_explanation_link') }}" class="muted accordion-toggle" data-toggle="collapse" href="#collapse1">
                {{ lang('home.participative.submission_explanation_link') }}
            </a>
            
            <div id="collapse1" class="collapse">
                <p class="justify accordion-inner">
                    {{ lang('home.participative.submission_explanation_text') }}
                </p>
            </div>
            
        </div>
        <div class="span1"></div>

        <div class="span5">
            <h2>{{ lang('home.searchable_title') }}</h2>

            <p>
                {{ lang('home.searchable_text') }} <br>
            </p>

            <br>
            <div class="align-center">
                <a href="{{ route('get_search') }}" title="{{ lang('home.searchable_link') }}" class="btn btn-primary btn-large align-center">{{ lang('home.searchable_link') }}</a>
            </div>
        </div>
    </div>

    <hr>

    <p>
        {{ lang('home.services_text') }}
    </p>

    <div class="row">
        <div class="span1"></div>
        
        <div class="span5">
            <h2>{{ lang('home.cross-promotion.title') }}</h2>

            <p>
                {{ lang('home.cross-promotion.text') }}
            </p>

        </div>
    </div>

</div> <!-- /#home -->