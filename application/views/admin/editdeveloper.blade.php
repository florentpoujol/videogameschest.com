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
<div id="editdeveloper_form">
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

        <?php
        foreach (Dev::$array_items as $item):
            $items = Config::get('vgc.'.$item);
            $options = get_array_lang($items, $item.'.');
            
            if (isset($old[$item])) $values = $old[$item];
            else $values = $dev->json_to_array($item);

            $size = count($items);
            if ($size > 10) {
                $size = 10;
            }
        ?>
        
        {{ Former::multiselect($item.'[]', $item)->options($options)->forceValue($values)->size($size) }}
        @endforeach

        <fieldset>
            <legend>{{ lang('developer.fields.socialnetworks') }}</legend>
            
            <?php
            $options = get_array_lang(Config::get('vgc.socialnetworks'), 'socialnetworks.');

            if (isset($old[$item])) $socialnetworks = clean_names_urls_array($old[$item]);
            else $socialnetworks = $dev->json_to_array('socialnetworks');

            $length = count($socialnetworks['names']);
            for ($i = 0; $i < $length; $i++):
            ?>
                {{ Former::select('socialnetworks[names][]', lang('developer.fields.socialnetworks_name'))->options($options)->forceValue($socialnetworks['names'][$i]) }} 
                {{ Former::url('socialnetworks[urls][]', lang('developer.fields.socialnetworks_url'))->forceValue($socialnetworks['urls'][$i]) }}
            @endfor

            @for ($i = 0; $i < 4; $i++)
                {{ Former::select('socialnetworks[names][]', lang('developer.fields.socialnetworks_name'))->options($options) }} 
                {{ Former::url('socialnetworks[urls][]', lang('developer.fields.socialnetworks_url')) }}
            @endfor
        </fieldset>
        
        <input type="submit" value="{{ lang('developer.edit.submit') }}" class="btn btn-primary">
    </form>
</div>
<!-- /#user_form --> 
