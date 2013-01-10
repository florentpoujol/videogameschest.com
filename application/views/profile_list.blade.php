<?php
$count = count($profiles);
?>
@if ($count <= 0)
    <p id="profile-list">
        {{ lang('search.no_profile_found') }}
    </p>
@else
    <section id="profile-list">
        <p id="profile-list">
            {{ lang('search.profiles_found', array('num'=>$count)) }}
        </p>

        @foreach ($profiles as $profile)
            <div class="media" id="profile-list">
                    
                <?php
                if ($profile->class_name == "developer") {
                    $src = $profile->logo;
                    $alt = $profile->name.' logo';
                }
                else {
                    $src = $profile->cover;
                    $alt = $profile->name.' box cover';
                }

                ?>
                <a class="pull-left" href="#">
                    <img src="{{ $src }}" alt="{{ $alt }}" class="media-object profile_list_caption" max-width="100px" max-width="100px">
                </a>
                
                <div class="media-body">    
                    <h4 class="media-heading"><a href="{{ route('get_'.$profile->class_name, array(name_to_url($profile->name))) }}">{{ $profile->name }}</h4>
                </div><!-- /.media-body -->
            </div> <!-- /.media -->
        @endforeach
    </section> <!-- /#profile-list -->
@endif