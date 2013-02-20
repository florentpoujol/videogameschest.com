<ul>
    <?php
    foreach ($profiles as $profile):
        $profile_link = route('get_profile_view', array($profile->type, name_to_url($profile_name)));
    ?>
        <li><a href="{{ $profile_link }}">{{ $profile->name }} ({{ $profile->type }})</a></li>
    @endforeach
</ul>