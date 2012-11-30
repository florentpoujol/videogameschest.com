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
     * Relationship method with the Users table
     * @return User The User instance, owner of this profile
     */
	public function user()
    {
        return $this->belongs_to('User');
    }
}