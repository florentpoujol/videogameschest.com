<?php

class Search extends ExtendedEloquent
{
    public static $table = 'searches';


    //----------------------------------------------------------------------------------

    /**
     * @param  array $input The search id, the array comming from the search form, or the array as json
     * @return Laravel\Database\Eloquent\Query or SearchError
     */
    public static function make($input)
    {
        $search_id = 0;
        if (is_numeric($input)) {
            $search_id = $input;
            $search = parent::find($input);

            if ( ! is_null($search)) $input = $search->data;
            else {
                $msg = lang('search.msg.id_not_found', array('id'=>$input));
                HTML::set_error($msg);
                return new SearchError($msg);
            }
        }

        if (is_string($input)) $input = json_decode($input, true);

        //dd($input);

        // profile type
        if ( ! isset($input['profile_type'])) $input['profile_type'] = 'game';
        $profile_type = $input['profile_type'];

        if ( ! in_array($profile_type, get_profile_types())) {
            Log::write('search error', "Wrong class '$profile_type' for search id='$search_id'");
            HTML::set_error(lang('common.msg.error'));
            return new SearchError("Wrong class '$profile_type' for search id='$search_id'");
        }

        
        // search for words in name or pitch
        if ( ! isset($input['search_words_mode'])) $input['search_words_mode'] = 'all';

        if ($input['search_words_mode'] == 'all') $words_where_mode = 'where';
        else $words_where_mode = 'or_where';


        if ( ! isset($input['search_words_in'])) $input['search_words_in'] = array();
        
        if ( ! isset($input['words_list'])) $input['words_list'] = '';
        $input['words_list'] = trim($input['words_list']);

        $search_words_in = array();

        if ($input['words_list'] != '' && ! empty($input['search_words_in'])) {
            $words_list = explode(' ', e($input['words_list']));
            
            foreach ($input['search_words_in'] as $field) {
                $search_words_in[$field] = $words_list;
            }
        }
        

        // array fields
        isset($input['array_fields']) ? $array_fields = $input['array_fields'] : $array_fields = array();
        isset($input['array_fields_where']) ? $array_fields_where = $input['array_fields_where'] : $array_fields_where = array();


        // proceed...
        return $profile_type::
        where(function($query) use ($words_where_mode, $search_words_in)
        {
            // words
            foreach ($search_words_in as $field => $words) {

                $query->or_where(function($query) use ($field, $words, $words_where_mode)
                {
                    foreach ($words as $value) {
                        $query->$words_where_mode($field, 'LIKE', '%'.$value.'%');
                    }
                });
            }
        })
        // AND
        ->where(function($query) use ($array_fields, $array_fields_where, $profile_type)
        {
            // array fields
            foreach ($array_fields as $field => $values) {
                
                if (in_array($field, $profile_type::$array_fields)) {
                    
                    $query->where(function($query) use ($array_fields, $array_fields_where, $field, $values)
                    {
                        foreach ($values as $value) {

                            if ($array_fields_where[$field] == 'all') {
                                $query->where($field, 'LIKE', '%'.$value.'%');
                            }
                            else {
                                $query->or_where($field, 'LIKE', '%'.$value.'%');
                            }
                        }
                    });
                }
            }
        });
    }

    public static function get_profiles($input)
    {   
        return Search::make($input)->where_privacy('public')->get();
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
            $search = parent::find($search);
        } else {
            if (is_array($search)) $search = json_encode($search);
            $search = parent::where_data($search)->first();
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
        if (is_array($data)) {
            // removes entry in array_fields_where when same key in array_field in not there
            foreach ($data['array_fields_where'] as $field => $value) {
                if ( ! isset($data['array_fields'][$field])) {
                    unset($data['array_fields_where'][$field]);
                }
            }

            $data = json_encode(clean_form_input($data));
        }

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


class SearchError
{
    public $error = '';

    public function __construct($error)
    {
        $this->error = $error;
    }

    public function get()
    {
        return array($this->error);
    }

    public function first()
    {
        return $this->error;
    }
}

// additionnal criteria
// useless now that Search::make() returns the eloquent model and not the array of profiles
/*if (is_array($additional_criteria)) {
    if ( ! isset($additional_criteria['where']) && ! isset($additional_criteria['or_where'])) {
        $additional_criteria = array('where' => $additional_criteria); 
    }

    foreach ($additional_criteria as $where_mode => $criteria) {
        $query->$where_mode(function($query) use ($criteria)
        {
            if ( ! isset($criteria[0])) { // $criteria is an assoc array
                $old_criteria = $criteria;
                $criteria = array();

                foreach ($old_criteria as $key => $value) {
                    $criteria[] = array(
                        'where_mode' => 'where',
                        'field' => $key,
                        'comparison' => '=',
                        'value' => $value,
                    );
                }
            }

            foreach ($criteria as $criterion) {
                if ( ! isset($criterion['where_mode'])) $criterion['where_mode'] = 'where';

                $query->$criterion['where_mode']($criterion['field'], $criterion['comparison'], $criterion['value']);
            }
        });
    }
}*/
