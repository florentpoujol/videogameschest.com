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
$reports = Reports::where_type($report_type)->get();
$profiles = array();
if (IS_ADMIN) {
    //$reports = Reports::where_type($report_type)->get();

    $users = User::where_type('dev')->get();
    foreach ($users as $user) {
        $profiles[] = $user->dev;
        $profiles = array_merge($profiles, $user->dev->games);
    }
} else {
    // looper sur les profils et afficher les rapports par profil
    $profiles[] = $user->dev;
    $profiles = array_merge($profiles, $user->dev->games);
}
?>


@if ( ! is_null($reports))
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                
                <th>Message</th>
                <th>Delete</th>
            </tr>
        </thead>

    @foreach ($profiles as $profile)
        <thead>Profile name {{ $profile->name}}</thead>

        @foreach ($profile->${report_type}_reports as $report)
            <tr>
                <!--<td><a href="{{ route('get_'.$profile_type, array($profile->id)) }}">{{ $profile->name }}</a></td>-->

                <td>{{ $report->message }}</td>
                
                <td>
                    ert
                </td>
            </tr>
        @endforeach
    @endforeach
    </table>
@else
    No {{ $report_type }} report.
@endif