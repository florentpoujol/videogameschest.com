@section('page_title')
    {{ lang('reports.title') }}
@endsection

<div class="reports">
    <h1>{{ lang('reports.title') }}</h1>

    <hr>

        

    <hr>

    <?php
    $tabs = array(array(
        'url' => route('get_reports', array('dev')),
        'label' => lang('reports.dev_title'),
    ));

    if (is_admin()) {
        $tabs[] = array(
            'url' => route('get_reports', array('admin')),
            'label' => lang('reports.admin_title'),
        );
    }
    ?>

    {{ Navigation::tabs($tabs) }}

    <p>
        <a href="{{ route('get_reports_feed', array($report_type, user_id(), user()->url_key)) }}" title="{{ lang('reports.rss_feed') }}">{{ icon('rss') }} {{ lang('reports.rss_feed') }}</a>
    </p>

    <?php
    //$reports = Report::where_type($report_type)->get();

    /*$profiles = array();
    if (is_admin()) {
        $users = User::where_type('user')->get();
        foreach ($users as $user) {
            $profiles = array_merge($profiles, $user->devs);
            $profiles = array_merge($profiles, $user->games);
        }
    } else {
        $profiles = array_merge($profiles, user()->devs);
        $profiles = array_merge($profiles, user()->games);
    }

    $reports = array();
    foreach ($profiles as $profile) {
        $reports = array_merge($reports, $profile->reports($report_type));
    }*/
    
    $reports = array();
    
    if (is_admin()) {
        $users = User::all();
        foreach ($users as $user) {
            $reports = array_merge($profiles, $user->reports($report_type));
        }
    } else {
        $reports = user()->reports('developer');
    }
    ?>

    @if ( ! empty($reports))
        {{ Former::open(route('post_editreports')) }}
            {{ Form::token() }}

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>{{ lang('reports.table.profile') }}</th>
                        <th>{{ lang('reports.table.message') }}</th>
                        <th>{{ Former::warning_submit(lang('reports.table.delete')) }}</th>
                    </tr>
                </thead>

                @foreach ($reports as $report)
                    <tr>
                        <td>
                            <a href="{{ route('get_'.$profile->class_name, array(name_to_url($profile->name))) }}">{{ $profile->name }}</a> ({{ $profile->class_name }})
                        </td>

                        <td class="span8">
                            {{ $report->message }}
                        </td>
                        
                        <td>
                            <input type="checkbox" name="reports[]" value="{{ $report->id }}">
                        </td>
                    </tr>
                @endforeach
            </table>
        </form>
    @else
        {{ lang('reports.no_report') }}
    @endif
</div> <!-- /#reports -->