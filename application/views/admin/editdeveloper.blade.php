<?php
$rules = array(
    'name' => 'required|min:5|unique:users,username',
    'logo' => 'url',
    'website' => 'url',
    'blogfeed' => 'url',
    'teamsize' => 'min:1'
);

$dev = Dev::find($profile_id);
Former::populate($dev);

$old = Input::old();
if ( ! empty($old)) Former::populate($old);
?>
<div id="editdeveloper">
    {{ Former::open_vertical('admin/editdeveloper')->rules($rules) }} 
        <legend>{{ lang('developer.edit.title') }}</legend>
        
        {{ Form::token() }}
        {{ Form::hidden('id', $profile_id) }}

        {{ Former::text('name', lang('developer.fields.name')) }}

        {{ Former::textarea('pitch', lang('developer.fields.pitch')) }}

        {{ Former::url('logo', lang('developer.fields.logo')) }}
        {{ Former::url('website', lang('developer.fields.website')) }}
        {{ Former::url('blogfeed', lang('developer.fields.blogfeed')) }}

        {{ Former::number('teamsize', lang('developer.fields.teamsize')) }}

        {{ Former::select('country')->options(get_array_lang(Config::get('vgc.countries'), 'countries.')) }}

        <hr>

        <!-- arrayitems + socialnetworks -->
        <div class="tabbable tabs-left">
            <ul class="nav nav-tabs nav-stacked" id="array_items_tabs">
                @foreach (Dev::$array_items as $item)
                <li><a href="#{{ $item }}" data-toggle="tab">{{ lang('developer.fields.'.$item) }}</a></li>
                @endforeach

                <li><a href="#socialnetworks" data-toggle="tab">{{ lang('developer.fields.socialnetworks') }}</a></li>
            </ul>

            <div class="tab-content">
                <?php
                foreach (Dev::$array_items as $item):
                    $items = Config::get('vgc.'.$item);
                    $options = get_array_lang($items, $item.'.');
                    
                    if (isset($old[$item])) $values = $old[$item];
                    else $values = $dev->$item;

                    $size = count($items);
                    if ($size > 10) {
                        $size = 10;
                    }
                ?>
                <div class="tab-pane" id="{{ $item }}">
                    {{ Former::multiselect($item.'[]', $item)->options($options)->forceValue($values)->size($size) }}
                </div>
                @endforeach

                <div class="tab-pane" id="socialnetworks">
                    <?php
                    $options = get_array_lang(Config::get('vgc.socialnetworks'), 'socialnetworks.');
                    $options = array_merge(array('' => lang('common.select-first-option')), $options);

                    if (isset($old[$item])) $socialnetworks = clean_names_urls_array($old[$item]);
                    else $socialnetworks = $dev->socialnetworks;

                    $length = count($socialnetworks['names']);
                    for ($i = 0; $i < $length; $i++):
                    ?>
                        <div class="control-group-inline">
                            {{ Former::select('socialnetworks[names][]', '')->options($options)->forceValue($socialnetworks['names'][$i]) }} 
                            {{ Former::url('socialnetworks[urls][]', '')->forceValue($socialnetworks['urls'][$i])->placeholder(lang('developer.fields.socialnetworks_url')) }}
                        </div>
                    @endfor

                    @for ($i = 0; $i < 4; $i++)
                        <div class="control-group-inline">
                            {{ Former::select('socialnetworks[names][]', '')->options($options) }} 
                            {{ Former::url('socialnetworks[urls][]', '')->placeholder(lang('developer.fields.socialnetworks_url')) }}
                        </div>
                    @endfor
                </div>
            </div>
        </div>    <!-- /.tabable -->
        <!-- array items + socialnetworks -->

        <hr>

        <input type="submit" value="{{ lang('developer.edit.submit') }}" class="btn btn-primary">
    </form>
</div><!-- /#editdeveloper --> 

@section('jQuery')
// from addgame
$('#array_items_tabs a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})
$('#array_items_tabs a:first').tab('show');
@endsection
