<?php
if ( ! isset($modal)) $modal = false;
?>
<div id="report_form">
    @if ($modal)
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    @endif

    <!-- <h3>{{ lang('reports.form_title') }}</h3> -->

    @if ($modal)
        </div> <!-- /.modal-header -->
    
        <div class="modal-body">
    @endif
    <br>
    
    {{ Former::open_vertical('admin/reports')->rules(array('message'=>'required|min:10')) }}
        {{ Form::token() }}
        {{ Former::hidden('action', 'create') }}
        {{ Former::hidden(strtolower(get_class($profile)).'_id', $profile->id) }}

        <p>
            {{ lang('reports.help') }}
        </p>
        
        {{ Former::textarea('message', '')->placeholder(lang('reports.message'))->rows(2)->value(Input::old('message')) }}

    @if ($modal)
        </div> <!-- /.modal-body -->
        <div class="modal-footer">
    @endif

        <input type="submit" value="{{ lang('reports.submit_dev') }}" class="btn btn-primary left-align"> 
        <input type="submit" name="admin" value="{{ lang('reports.submit_admin') }}" class="btn btn-danger btn-small">
    </form>
    
    @if ($modal)
        </div> <!-- /.modal-footer -->
    @endif    
</div>
