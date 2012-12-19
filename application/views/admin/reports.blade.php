@section('page_title')
    {{ lang('reports.title') }}
@endsection


<h2>{{ lang('reports.title') }}</h2>

<?php
$tabs = array(array(
    'url' => route('get_reports', array('dev')),
    'label' => lang('reports.dev_title'),
));

if (IS_ADMIN) {
    $tabs[] = array(
        'url' => route('get_reports', array('admin')),
        'label' => lang('reports.admin_title'),
    );
}
?>

{{ Navigation::tabs($tabs) }}

<?php
$reports = Report::where_type($report_type)->get();

$profiles = array();
if (IS_ADMIN) {
    $users = User::where_type('dev')->get();
    foreach ($users as $user) {
        if ($user->dev != null) {
            $profiles[] = $user->dev;
            $profiles = array_merge($profiles, $user->dev->games);
        }
    }
} else {
    $profiles[] = user()->dev;
    $profiles = array_merge($profiles, user()->dev->games);
}
?>

{{ Former::open('admin/reports') }}
{{ Form::token() }}
{{ Former::hidden('action', 'delete') }}

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>{{ lang('reports.table.profile') }}</th>
            <th>{{ lang('reports.table.message') }}</th>
            <th><input type="submit" value="{{ lang('reports.table.delete') }}" class="btn btn-warning"></th>
        </tr>
    </thead>

@foreach ($profiles as $profile)
    
    <?php
    $reports = $profile->reports($report_type);
    ?> 
    @foreach ($reports as $report)
        <tr>
            <td>
                <a href="{{ route('get_'.$profile->class_name, array($profile->id)) }}">{{ $profile->name }}</a> ({{ $profile->class_name }})
            </td>

            <td>
                {{ $report->message }}
            </td>
            
            <td>
                <input type="checkbox" name="reports[]" value="{{ $report->id }}">
            </td>
        </tr>
    @endforeach

@endforeach
</table>

</form>
