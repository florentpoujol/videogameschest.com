<?php

class DBConfig extends Eloquent
{
	
    public static $table = 'config';
    public static $cache = array();

    //----------------------------------------------------------------------------------

    /**
     * get config data from the DB
     * @param  string $key         The key to look for
     * @param  mixed $replacement The replacement value if the key is not found in the DB
     * @return string              The value associated with the provided key
     */
    public static function get($key, $replacement = null)
    {
        if (isset(static::$cache[$key])) return static::$cache[$key];

        $config = DBConfig::where('key', '=', $key)->first();

        if (is_null($config)) return $replacement;
        else {
            static::$cache[$key] = $config->value;
            return $config->value;
        }
    }


    /**
     * Insert or update config data
     * @param  string  $key   The key to look for
     * @param  mixed  $value The value to save with the key
     * @param  boolean $json  Tell wether to encode the value in json before saving
     */
    public static function put($key, $value, $json = false)
    {
        if ($json) $value = json_encode($value);

        static::$cache[$key] = $value;

        $config = DBConfig::where('key', '=', $key)->first();

        if (is_null($config)) { // new key, create the entry
            $config = new DBConfig(array(
                'key' => $key,
                'value' => $value
            ));
        } else { // update the entry
            $config->value = $value;
            $config->save();
        }
    }


    /**
     * Tell wether the config table has a key
     * @param  string  $key The key to look for
     * @return boolean      If the table contains the key
     */
    public static function has($key)
    {
        if (isset(static::$cache[$key])) return true;

        $config = DBConfig::where('key', '=', $key)->first();

        if (is_null($config)) return false;
        else return true;
    }
}   
