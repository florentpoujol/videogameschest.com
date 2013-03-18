@section('page_title')
    {{ lang('vgc.reports.title') }}
@endsection

<div class="reports">
    <h1>{{ lang('vgc.reports.title') }}</h1>

    <hr>

    <p>
        <a href="{{ route('get_reports_feed', array(user_id(), user()->url_key)) }}" title="{{ lang('vgc.reports.rss_feed') }}">{{ icon('rss') }} {{ lang('vgc.reports.rss_feed') }}</a>
    </p>

    <?php  
    $reports = array();
    
    if (is_admin()) {
        $reports = Report::order_by('created_at', 'desc')->get();
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
                        <th>{{ lang('vgc.reports.table.profile') }}</th>
                        <th>{{ lang('vgc.reports.table.message') }}</th>
                        <th>{{ Former::warning_submit(lang('vgc.reports.table.delete')) }}</th>
                    </tr>
                </thead>

                @foreach ($reports as $report)
                    <?php
                    $profile_type = $report->profile->type;
                    $profile_name = $report->profile->name;
                    ?>
                    <tr>
                        <td>
                            {{ $report->created_at }}
                        </td>

                        <td>
                            <a href="{{ route('get_profile_view', array($profile_name, name_to_url($profile_name))) }}">{{ $profile_name }}</a>
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
        {{ lang('vgc.reports.no_report') }}
    @endif
</div> <!-- /#reports -->