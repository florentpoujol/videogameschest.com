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
        $search_id = 0;
        if (is_numeric($input)) {
            $search_id = $input;
            $search = parent::find($input);

            if ( ! is_null($search)) $input = $search->data;
            else {
                HTML::set_error(lang('search.msg.id_not_found', array('id'=>$input)));
                $input = array();
            }
        }

        if (is_string($input)) $input = json_decode($input, true);


        // 
        $profile_type = $input['profile_type'];

        if ( ! in_array($profile_type, get_profiles_types())) {
            Log::write('search error', "Wrong class '$profile_type' for search id='$search_id'");
            HTML::set_error(lang('common.msg.error'));
            return Redirect::back();
        }

        
        // search for words in name or pitch
        $search_words_in = array();
        $input['words_list'] = trim($input['words_list']);

        if ($input['words_where'] == 'all') $words_where = 'where';
        else $words_where = 'or_where';

        if ($input['words_list'] != '' && (isset($input['search_in_name']) || isset($input['search_in_pitch']))) {
            $words_list = explode(' ', e($input['words_list']));
            
            if (isset($input['search_in_name'])) $search_words_in['name'] = $words_list;
            if (isset($input['search_in_pitch'])) $search_words_in['pitch'] = $words_list;
        }

        // array items
        isset($input['arrayitems']) ? $array_items = $input['arrayitems'] : $array_items = array();


        // proceed...
        $profiles = $profile_type::where_privacy('public')
        ->where(function($query) use ($search_words_in, $array_items, $words_where, $profile_type)
        {
            // words
            foreach ($search_words_in as $field => $words) {

                $query->or_where(function($query) use ($field, $words, $words_where)
                {
                    foreach ($words as $value) {
                        $query->$words_where($field, 'LIKE', '%'.$value.'%');
                    }
                });

            }
            
            // array items
            foreach ($array_items as $field => $values) {
                
                if (in_array($field, $profile_type::$array_fields)) {
                    
                    $query->where(function($query) use ($array_items, $field, $values)
                    {
                        foreach ($values as $value) {
                            if ($array_items[$field.'_where'] == 'all') {
                                $query->where($field, 'LIKE', '%'.$value.'%');
                            }
                            else {
                                $query->or_where($field, 'LIKE', '%'.$value.'%');
                            }
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
