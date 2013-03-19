<div id="register">
    <h2>{{ lang('register.title') }}</h2>

    <hr>

    <div class="row">
        <div class="span4">
            @include('forms/register')
        </div>

        <div class="span8">
            <h3>{{ lang('vgc.register.why_register_title') }}</h3>

            <p>
                {{ lang('vgc.register.why_register_help') }}
            </p>
        </div>
    </div>
    
</div> <!-- /#register -->
