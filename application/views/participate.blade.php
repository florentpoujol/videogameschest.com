<div id="participate">
    <div class="pull-right muted" id="participate-icon">
        {{ icon('group') }}
    </div>

    <h1>{{ lang('participate.title') }}</h1>

    <hr>

    <p>
        {{ lang('participate.text') }}
    </p>

    <br>

    <div class="align-center">
        @if (is_guest())
            <a href="{{ route('get_register_page') }}" title="{{ lang('participate.register') }}" class="btn btn-primary btn-large">{{ lang('participate.register') }}</a>
        @else
            <a href="{{ route('get_game_create') }}" title="{{ lang('game.add.title') }}" class="btn btn-primary btn-large">{{ lang('game.add.title') }}</a> 

            <a href="{{ route('get_developer_create') }}" title="{{ lang('developer.add.title') }}" class="btn btn-primary btn-large">{{ lang('developer.add.title') }}</a> 
        @endif
    </div>

    <br>

    <hr>

    <div class="row">
        <div class="span5">
            <h3>{{ lang('reports.title') }}</h3>
            
            <p>
                {{ lang('participate.report_text') }}
            </p>
        </div>

        <div class="span7">
            <h3>{{ lang('participate.ownership_title') }}</h3>
            
            <p>
                {{ lang('participate.ownership_text') }}
            </p>
        </div>
    </div>

    

</div>