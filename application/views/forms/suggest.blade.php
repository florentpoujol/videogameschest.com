@layout('layouts/colorbox')

@section('colorbox_content')
    <div class="suggest-form">
        {{ Former::open_vertical(route('post_suggest'))->rules(array('url'=>'required|url')) }}
            {{ Form::token() }}
            
            <p>
                {{ lang('vgc.suggest.form_help') }}
            </p>
            
            {{ Former::url('url', '')->placeholder(lang('vgc.common.url'))->value(Input::old('url')) }}
            
            {{ antiBot() }} 
            
            {{ Former::submit(lang('vgc.common.submit')) }}
        {{ Former::close() }}
    </div>
@endsection