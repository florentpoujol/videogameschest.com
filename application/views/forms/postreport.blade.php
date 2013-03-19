<?php
if ( ! isset($modal)) $modal = false;
?>
<div id="report_form">
    @if ($modal)
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    @endif

    <!-- <h3>{{ lang('vgc.reports.form_title') }}</h3> -->

    @if ($modal)
        </div> <!-- /.modal-header -->
    
        <div class="modal-body">
    @endif
    <br>
    
    {{ Former::open_vertical(route('post_reports_create'))->rules(array('message'=>'required|min:10')) }}
        {{ Form::token() }}
        {{ Former::hidden($profile->class_name.'_id', $profile->id) }}

        <p>
            {{ lang('vgc.reports.help') }}
        </p>
        
        {{ Former::textarea('message', '')->placeholder(lang('vgc.reports.message'))->rows(2)->value(Input::old('message'))->class('span4') }}
        
        {{ antiBot() }} 
        
    @if ($modal)
        </div> <!-- /.modal-body -->
        <div class="modal-footer">
    @endif
        
        {{ Former::submit(lang('vgc.common.submit')) }}
    {{ Former::close() }}
    
    @if ($modal)
        </div> <!-- /.modal-footer -->
    @endif    
</div>
