<div>
    <h4>Suggest a game</h4>
    
    {{ Former::open_vertical(route('post_suggestion_create'))->rules(array('url'=>'required|url')) }}
        {{ Form::token() }}
        
        <p>
            {{ lang('suggestion.form_help') }}
        </p>
        
        {{ Former::url('url', '')->placeholder(lang('common.url'))->value(Input::old('url')) }}
        
        {{ antiBot() }} 
        
        {{ Former::submit(lang('common.submit')) }}
    {{ Former::close() }}
</div>
