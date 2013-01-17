<?php
    $tiles_width = 4;// number of tile by the width
?>
<section id="profile-list-tiles" class="">
    <div class="row">
        @for ($i = 1; $i <= count($profiles); $i++)
            <?php
            $profile = $profiles[$i-1];

            if ($profile->class_name == "developer") {
                $src = $profile->logo;
                $alt = $profile->name.' logo';
            }
            else {
                $src = $profile->cover;
                $alt = $profile->name.' box cover';
            }
            ?>

            <div class="span3 tile">
                <a href="{{ route('get_'.$profile->class_name, array(name_to_url($profile->name))) }}">
                    <img src="{{ $src }}" alt="{{ $alt }}" class="profile-img"> <br>
                {{ $profile->name }}</a>
            </div>

            @if ($i%$tiles_width == 0)
    </div>
    <div class="row">
            @endif
        @endfor
    </div>
</section> <!-- /#profile-list -->
