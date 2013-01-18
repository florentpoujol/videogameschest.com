<?php

class Search extends ExtendedEloquent
{
    public static $table = 'searches';


    //----------------------------------------------------------------------------------

    /**
     * @param  array $input The array comming from the search form
     * @param  boolean $register_in_db Tell wether or not checking if the search is already registered in the DB
     * (and register it if it isn't)
     * @return array        The finded profiles
     */
    public static function get_profiles($input)
    {   
        if (is_numeric($input)) {
            $search = static::has($input);

            if ($search !== false) $input = $search->data;
            else {
                HTML::set_error(lang('search.msg.id_not_found', array('id'=>$input)));
                $input = array();
            }
        }

        if (is_string($input)) $input = json_decode($input, true);

        //var_dump($input);


        // class (dev or game)
        $class = $input['class'];

        if ($class != 'developer' && $class != 'game') {
            // problem
            return Redirect::back();
        }

        
        // words in name or title
        $words = array();

        if ($input['words_contains'] == 'all') $words_where = 'where';
        else $words_where = 'or_where';

        if (trim($input['words_list']) != '' && (isset($input['search_in_name']) || isset($input['search_in_pitch']))) {
            $words_list = explode(' ', e(trim($input['words_list'])));
            
            if (isset($input['search_in_name'])) $words['name'] = $words_list;
            if (isset($input['search_in_pitch'])) $words['pitch'] = $words_list;
        }

        // array items
        isset($input['arrayitems']) ? $array_items = $input['arrayitems'] : $array_items = array();


        // proceed...
        $profiles = $class::where_privacy('public')
        ->where(function($query) use ($words, $input, $array_items, $words_where, $class)
        {
            // words
            foreach ($words as $field => $values) {
                $query->or_where(function($query) use ($field, $values, $input, $words_where)
                {
                    foreach ($values as $value) {
                        //if ($input['words_search_mode'] == 'part') $value = '%'.$value.'%';

                        $query->$words_where($field, 'LIKE', '%'.$value.'%');
                    }
                });
            }
            
            // array items
            foreach ($array_items as $field => $values) {
                if (in_array($field, $class::$array_fields)) {
                    $query->where(function($query) use ($field, $values)
                    {
                        foreach ($values as $value) {
                            $query->or_where($field, 'LIKE', '%'.$value.'%');
                        }
                    });
                }
            }
        })
        ->get();

        return $profiles;
    }

    /**
     * Check if a search exists
     * @param  mixed  $search The search id, or search data as array or json
     * @return mixed     null if the search does not exists, or the Search model
     */
    public static function get($search) 
    {   
        // if search is a search id
        if (is_numeric($search)) {
            $search = static::find($search);
        } else {
            if (is_array($search)) $search = json_encode($search);
            $search = static::where_data($search)->first();
        }

        return $search;
    }

    public static function has($search) 
    {   
        // if search is a search id
        $search = static::get($search);

        if (is_null($search)) return false;
        else return true;
    }

    /**
     * Add a search in the DB if it does not exists yet
     * Returns the search id
     * @param  mixed $data The search input (array or json)
     * @return id       The search id
     */
    public static function create($data)
    {
        if (is_array($data)) $data = json_encode(clean_form_input($data));

        $search = static::get($data);

        if (is_null($search)) {
            $search = parent::create(array('data'=>$data));
        }

        return $search;
    }


    //----------------------------------------------------------------------------------
    // GETTER

    public function get_array_data()
    {
        return json_decode($this->get_attribute('data'), true);
    }
}
