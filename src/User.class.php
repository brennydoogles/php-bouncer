<?php
/**
 * Created with JetBrains PhpStorm.
 * User: Brendon Dugan <wishingforayer@gmail.com>
 * Date: 7/4/12
 * Time: 3:04 PM
 *
 */
class User
{
    private $roles;
    public function __construct()
    {
        $this->roles = array();
    }

    public function addRole($name){
        if(array_search($name, $this->roles) === false && is_string($name)){
            array_push($this->roles, $name);
        }
    }

    public function getRoles(){
        return $this->roles;
    }
}
