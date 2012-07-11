<?php
	/**
	 * Created with JetBrains PhpStorm.
	 * User: Brendon Dugan <wishingforayer@gmail.com>
	 * Date: 7/4/12
	 * Time: 3:04 PM
	 *
	 */
	class BouncerUser{
		private $bouncerRoles;

		public function __construct(){
			$this->bouncerRoles = array();
		}

		public function addRole($name){
			if(array_search($name, $this->bouncerRoles) === false && is_string($name)){
				array_push($this->bouncerRoles, $name);
			}
		}

		// Adding a comment to force a re-commit of all files.

		public function getRoles(){
			return $this->bouncerRoles;
		}
	}
