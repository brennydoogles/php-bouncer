<?php
/**
 * Created with JetBrains PhpStorm.
 * User: Brendon Dugan <wishingforayer@gmail.com>
 * Date: 7/4/12
 * Time: 3:04 PM
 *
 */
class BouncerUser
{
	/**
	 * @var array
	 */
	private $bouncerRoles;

	/**
	 *
	 */
	public function __construct()
    {
        $this->bouncerRoles = array();
    }

	/**
	 * @param $name
	 */
	public function addRole($name)
    {
        if (array_search($name, $this->bouncerRoles) === false && is_string($name)) {
            array_push($this->bouncerRoles, $name);
        }
    }

	/**
	 * @return array
	 */
	public function getRoles()
    {
        return $this->bouncerRoles;
    }

	/**
	 * @param string $role
	 * @return bool
	 */
	public function hasRole($role){
		return (array_search($role, $this->bouncerRoles) !== false);
	}
}
