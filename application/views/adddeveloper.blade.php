<?php
$rules = array(
    'name' => 'required|min:5',
    'email' => 'required|min:5|email',
    'logo' => 'url',
    'website' => 'url',
    'blogfeed' => 'url',
    'teamsize' => 'min:1'
);

$old = Input::old();
if ( ! empty($old)) Former::populate($old);

if (CONTROLLER == 'admin' && IS_ADMIN) {
    $rules['email'] = 'min:5|email';
    $users = User::get(array('id', 'username'));
    $privacy = array_set_values_as_keys(Config::get('vgc.privacy'));
}
?>
<div id="adddeveloper">
    <h2>{{ lang('developer.add.title') }}</h2>

<div class="row">
    {{ Former::open_vertical('admin/adddeveloper')->rules($rules) }}
        <div class="span5">
        {{ Form::token() }}
        {{ Form::hidden('controller', CONTROLLER) }}

        {{ Former::text('name', lang('common.name'))->help(lang('developer.name_help')) }}

        @if (CONTROLLER == 'admin' && IS_ADMIN)
            {{ Former::email('email', lang('common.email'))->help('This will create a new user, OR choose the user below') }}
            
            {{ Former::select('user_id', 'User')->fromQuery($users)  }}

            {{ Former::select('privacy')->options($privacy) }}
        @else
            {{ Former::email('email', lang('common.email')) }}

            @if (CONTROLLER == 'admin')
                {{ Former::hidden('privacy', 'private') }}
            @elseif (CONTROLLER == 'adddeveloper')
                {{ Former::hidden('privacy', 'submission') }}
            @endif
        @endif
        
        {{ Former::textarea('pitch', lang('developer.pitch')) }}

        {{ Former::url('logo', lang('common.logo')) }}
        {{ Former::url('website', lang('common.website')) }}
        {{ Former::url('blogfeed', lang('common.blogfeed')) }}

        {{ Former::number('teamsize', lang('common.teamsize'))->value(1) }}

        {{ Former::select('country', lang('common.country'))->options(get_array_lang(Config::get('vgc.countries'), 'countries.')) }}

    

        </div>
        <div class="span7">

        <!-- array items + socialnetworks -->
        <div class="tabbable tabs-left">
            <ul class="nav nav-tabs nav-stacked" id="array_items_tabs">
                @foreach (Dev::$array_items as $item)
                <li><a href="#{{ $item }}" data-toggle="tab">{{ lang($item.'.title') }}</a></li>
                @endforeach

                <li><a href="#socialnetworks" data-toggle="tab">{{ lang('socialnetworks.title') }}</a></li>
            </ul>

            <div class="tab-content">
                <?php
                foreach (Dev::$array_items as $item):
                    $items = Config::get('vgc.'.$item);
                    $options = get_array_lang($items, $item.'.');

                    $values = array();
                    if (isset($old[$item])) $values = $old[$item];

                    $size = count($items);
                    if ($size > 10) $size = 10;
                ?>
                <div class="tab-pane" id="{{ $item }}">
                    <p>{{ lang('developer.'.$item.'_help') }}</p>
                    {{ Former::multiselect($item.'[]', '')->options($options)->value($values)->size($size) }}
                </div>
                @endforeach

                <div class="tab-pane" id="socialnetworks">
                    <?php
                    $options = get_array_lang(Config::get('vgc.socialnetworks'), 'socialnetworks.');
                    $options = array_merge(array('' => lang('common.select-first-option')), $options);

                    $values = array();
                    if (isset($old['socialnetworks'])) $values = clean_names_urls_array($old['socialnetworks']);
                    ?>
                    @for ($i = 0; $i < 4; $i++)
                        <?php
                        $name = isset($values['names'][$i]) ? $values['names'][$i] : '';
                        $url = isset($values['urls'][$i]) ? $values['urls'][$i] : '';
                        ?>
                        <div class="control-group-inline">
                            {{ Former::select('socialnetworks[names][]', '')->options($options)->value($name) }}  
                            {{ Former::url('socialnetworks[urls][]', '')->placeholder(lang('common.url'))->value($url) }}
                        </div>
                    @endfor

                </div>
            </div>
        </div><!-- /.tabable -->
        <!-- array items + socialnetworks -->
        
        <hr>

        <input type="submit" value="{{ lang('developer.add.submit') }}" class="btn btn-primary clearfix">
        </div>
    </form>
    </div>
</div><!-- /#adddeveloper --> 

@section('jQuery')
// from addgame
$('#array_items_tabs a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})
$('#array_items_tabs a:first').tab('show');
@endsection
