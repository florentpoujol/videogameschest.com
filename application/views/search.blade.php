<div id="search_form">
    <h2>Search</h2>
    {{ Former::open('search') }}
        {{ Form::token() }}

        {{ Former::text('name') }}

        <?php 
        echo Former::radios('type')->radios(array(
        'Developer' => array('value' => 'developer', 'checked'=>'checked'),
        'game' => array('value' => 'game'),
        ));
        ?>

        {{ Former::submit('Search') }}

    </form>
</div>
