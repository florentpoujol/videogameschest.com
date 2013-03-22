<div id="report_form">
    {{ Former::open_vertical(route('post_reports_create'))->rules(array('message'=>'required|min:10')) }}
        {{ Form::token() }}
        {{ Former::hidden('profile_id', $profile->id) }}

        <p>
            {{ lang('vgc.reports.help') }}
        </p>
        
        {{ Former::textarea('message', '')->placeholder(lang('vgc.reports.message'))->rows(2)->value(Input::old('message'))->class('span4') }}
        
        {{ antiBot() }} 
        
        {{ Former::submit(lang('vgc.common.submit')) }}
    {{ Former::close() }}
</div>
