<?php
// $profile_type
// $profile_list
?>
<div id="profile-list-form">

    {{ Former::hidden('profile_type', $profile_type) }}

    {{ Former::text('name', lang('common.name'))->help(lang('common.add_names_help')) }}

    <input type="submit" name="add" value="{{ lang('common.add_profile') }}" class="btn btn-success">

    <hr>

    @if ( ! empty($profile_list))
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>{{ lang('common.name') }}</th>
                    <th><input type="submit" name="delete" value="{{ lang('common.delete') }}" class="btn btn-warning"></th>
                </tr>
            </thead>

            @foreach ($profile_list as $profile_id)
                <?php
                $profile = $profile_type::find($profile_id);
                ?>
                <tr>
                    <td>
                        <a href="{{ route('get_profile_view', array($profile->type, name_to_url($profile->name))) }}">{{ $profile->name }}</a> ({{ $profile->type }})
                    </td>
                    
                    <td>
                        <input type="checkbox" name="ids_to_delete[]" value="{{ $profile_id }}">
                    </td>
                </tr>
            @endforeach
        </table>
    @else
        <p>
            {{ lang('common.profile_list_empty') }}
        </p>
    @endif
</div>