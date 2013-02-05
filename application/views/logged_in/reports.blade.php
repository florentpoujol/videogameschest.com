@section('page_title')
    {{ lang('reports.title') }}
@endsection

<div class="reports">
    <h1>{{ lang('reports.title') }}</h1>

    <hr>

    <?php
    $tabs = array(array(
        'url' => route('get_reports', array('developer')),
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
        @if (is_admin())
            <a href="{{ route('get_reports_feed', array($report_type, user_id(), user()->url_key)) }}" title="{{ lang('reports.rss_feed') }}">{{ icon('rss') }} {{ lang('reports.rss_feed') }}</a>
        @else
            <a href="{{ route('get_reports_feed', array('developer', user_id(), user()->url_key)) }}" title="{{ lang('reports.rss_feed') }}">{{ icon('rss') }} {{ lang('reports.rss_feed') }}</a>
        @endif
    </p>

    <?php  
    $reports = array();
    
    if (is_admin()) {
        $reports = Report::where_type($report_type)->order_by('created_at', 'desc')->get();
    } else {
        $reports = user()->reports('developer');
    }
    ?>

    @if ( ! empty($reports))
        {{ Former::open(route('post_reports_update')) }}
            {{ Form::token() }}

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>{{ lang('common.date') }}</th>
                        <th>{{ lang('reports.table.profile') }}</th>
                        <th>{{ lang('reports.table.message') }}</th>
                        <th>{{ Former::warning_submit(lang('reports.table.delete')) }}</th>
                    </tr>
                </thead>

                @foreach ($reports as $report)
                    <?php
                    $class_name = $report->profile->class_name;
                    $profile_name = $report->profile->name;
                    ?>
                    <tr>
                        <td>
                            {{ $report->created_at }}
                        </td>

                        <td>
                            <a href="{{ route('get_'.$class_name, array(name_to_url($profile_name))) }}">{{ $profile_name }}</a> ({{ $class_name }})
                        </td>

                        <td class="span5">
                            {{ $report->message }}
                        </td>
                        
                        <td >
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