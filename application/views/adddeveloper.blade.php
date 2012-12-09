<?php
$rules = array(
    'name' => 'required|min:5|unique:users,username',
    'email' => 'required|min:5|unique:users|email',
    'logo' => 'url',
    'website' => 'url',
    'blogfeed' => 'url',
    'teamsize' => 'min:1'
);

$old = Input::old();
if ( ! empty($old)) Former::populate($old);
?>

<div id="adddeveloper_form">
    {{ Former::open_vertical('admin/adddeveloper')->rules($rules) }} 
        <legend>{{ lang('developer.add.title') }}</legend>
        {{ Form::token() }}
        
        {{ Former::text('name', lang('developer.fields.name')) }}
        {{ Former::email('email', lang('developer.fields.email')) }}

        {{ Former::textarea('pitch', lang('developer.fields.pitch')) }}

        {{ Former::url('logo', lang('developer.fields.logo')) }}
        {{ Former::url('website', lang('developer.fields.website')) }}
        {{-- Former::url('blogfeed', lang('developer.fields.blogfeed')) --}}

        {{ Former::number('teamsize', lang('developer.fields.teamsize'))->value(1) }}

        {{ Former::select('country', lang('developer.fields.country'))->options(get_array_lang(Config::get('vgc.countries'), 'countries.')) }}

        <?php
        foreach (Dev::$array_items as $item):
            $items = Config::get('vgc.'.$item);
            $options = get_array_lang($items, $item.'.');

            $values = array();
            if (isset($old[$item])) $values = $old[$item];

            $size = count($items);
            if ($size > 10) $size = 10;
        ?>
            {{ Former::multiselect($item.'[]', lang('developer.fields.'.$item))->options($options)->value($values)->size($size) }}
        @endforeach
        
        <fieldset>
            <legend>{{ lang('developer.fields.socialnetworks') }}</legend>
            
            <?php
            $options = get_array_lang(Config::get('vgc.socialnetworks'), 'socialnetworks.');
            $values = array();
            if (isset($old['socialnetworks'])) $values = clean_names_urls_array($old['socialnetworks');
            ?>
            @for ($i = 0; $i < 4; $i++)
                <?php
                $name = isset($values['names'][$i]) ? $values['names'][$i] : '';
                $url = isset($values['urls'][$i]) ? $values['urls'][$i] : '';
                ?>
                {{ Former::select('socialnetworks[names][]', lang('developer.fields.socialnetworks_name'))->options($options)->value($name) }} 
                {{ Former::url('socialnetworks[urls][]', lang('developer.fields.socialnetworks_url'))->value($url) }}
            @endfor
        </fieldset>

        <input type="submit" value="{{ lang('developer.add.submit') }}" class="btn btn-primary">
    </form>
</div>
<!-- /#user_form --> 

