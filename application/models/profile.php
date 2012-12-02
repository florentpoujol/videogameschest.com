<?php

class Profile extends Eloquent
{

	public static $timestamps = true;

	//----------------------------------------------------------------------------------

	/**
	 * Find a record by the primary key.
	 *
	 * @param  int     $id
	 * @param  array   $columns
	 * @return object
	 */
	public static function find($id, $type = null, $columns = array('*'))
	{
		if (is_array($type)) 
		{
			$columns = $type;
			$type = null;
		}

		if ($type != null) {
			$profile = Profile::where('id', '=', $id)->where('type', '=', $type)->first($columns);
		}
		else {
			$profile = Profile::where('id', '=', $id)->first($columns);
		}

		if ($profile == null) {
			return null;
		}

		$profile->data_array = json_decode($profile->data, true);
		return $profile;
	}


	//----------------------------------------------------------------------------------

	/**
	 * Created an array with data from the database, 
	 * with the specified $fields as key and value
	 * @param  string $key   The field used as array key
	 * @param  string $value The field used as array value
	 * @param  string $type  The type of profile
	 * @return array         The generated array
	 */
	public static function get_array($key, $value, $type = 'any')
	{
		if ($type == 'any') {
			$profiles = Profile::get(array($key, $value));
		}
		else {
			$profiles = Profile::where('type', '=', $type)->get(array($key, $value));
		}

		$array = array();

		foreach ($profiles as $profile) {
			$array[$profile->$key] = $profile->$value;
		}

		return $array;
	}


	//----------------------------------------------------------------------------------

	/**
     * Relationship method with the Users table
     * @return User The User instance, owner of this profile
     */
	public function user()
    {
        return $this->belongs_to('User');
    }
}