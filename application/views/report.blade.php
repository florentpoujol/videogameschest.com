<div id="reports_form">
    <h3>{{ lang('reports.form_title') }}</h3>

    {{ Former::open_vertical('admin/reports')->rules(array('message'=>'required|min:10')) }}

        {{ Form::token() }}
        {{ Former::hidden('action', 'create') }}
        {{ Former::hidden(strtolower(get_class($profile)).'_id', $profile->id) }}

        {{ Former::textarea('message', '')->placeholder(lang('reports.message'))->rows(2)->value(Input::old('message')) }}

        <input type="submit" value="{{ lang('reports.submit_dev') }}" class="btn btn-primary"> <br>
        <br>
        <input type="submit" name="admin" value="{{ lang('reports.submit_admin') }}" class="btn btn-danger btn-small">
    </form>
</div>
