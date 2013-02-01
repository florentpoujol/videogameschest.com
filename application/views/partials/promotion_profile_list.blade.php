<ul>
    <?php
    foreach ($profiles as $profile):
        $class_name = $profile->class_name;
        $profile_name = $profile->name;
        $profile_link = route('get_'.$class_name, array(name_to_url($profile_name)));
    ?>
        <li><a href="{{ $profile_link }}">{{ $profile_name }} ({{ $class_name }})</a></li>
    @endforeach
</ul>