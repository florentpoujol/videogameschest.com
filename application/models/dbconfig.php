<?php

class DBConfig extends Eloquent {
	
    public static function get($key, $replacement = null)
    {
        $config = DBConfig::where('key', '=', $key)->first();

        if (is_null($config)) return $replacement;
        else return $config->value;
    }

    public static function put($key, $value, $json = false)
    {
        $config = DBConfig::where('key', '=', $key)->first();

        if (is_null($config)) { // new key, create the entry
            $config = new DBConfig(array(
                'key' => $key,
                'value' => $value
            ));
        } else { // update the entry
            if ($json) $value = json_encode($value);

            $config->value = $value;
            $config->save();
        }
    }

    public static function has($key)
    {
        $config = DBConfig::where('key', '=', $key)->first();

        if (is_null($config)) return false;
        else return true;
    }
}   
