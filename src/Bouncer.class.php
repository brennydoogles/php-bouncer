<?php
/**
  *
  *  Copyright 2012 Brendon Dugan
  *
  *     Licensed under the Apache License, Version 2.0 (the "License");
  *     you may not use this file except in compliance with the License.
  *     You may obtain a copy of the License at
  *
  *         http://www.apache.org/licenses/LICENSE-2.0
  *
  *     Unless required by applicable law or agreed to in writing, software
  *     distributed under the License is distributed on an "AS IS" BASIS,
  *     WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  *     See the License for the specific language governing permissions and
  *     limitations under the License.
  *
  */

/**
 * Created with JetBrains PhpStorm.
 * User: Brendon Dugan <wishingforayer@gmail.com>
 * Date: 7/4/12
 * Time: 9:39 AM
 *
 */
class Bouncer
{
    /**
     *
     */
    public function __construct()
    {
        // TODO: Implement __construct() method.
    }


    /**
     * @param $roles array An array of roles the current user possesses.
     * @param $url string The URL of the page the user is trying to access.
     * @returns $granted bool A Boolean value reflecting whether or not the user is allowed to access $url.
     */
    public function verifyAccess($roles, $url){

    }

    public function addRole($name, $pages, $roles = null, $replaces = null){

    }

}
