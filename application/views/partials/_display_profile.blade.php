<section id="display-profiles-list">
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
                <h4 class="media-heading"><a href="{{ route('get_'.$profile->class_name, array(name_to_url($profile->name))) }}">{{ $profile->name }}</a></h4>
            </div><!-- /.media-body -->
        </div> <!-- /.media -->
    @endforeach
</section> <!-- /#display-profiles -->
