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

require("BouncerRole.class.php");
require("BouncerProtectionMethod.class.php");

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
     * @var $roles BouncerRole[]
     */
    protected $roles;

    /** @var $redirectProtectionMethod int */
    protected $redirectProtectionMethod;

    /** @var $redirectProtectionVar String */
    protected $redirectProtectionVar;

    /** @var $maxRedirectsBeforeProtection int */
    protected $maxRedirectsBeforeProtection;

    /**
     *
     */
    public function __construct()
    {
        $this->roles = array();
        $this->redirectProtectionMethod = BouncerProtectionMethod::Session;
        $this->redirectProtectionVar = "BouncerRedirectCount";
        $this->maxRedirectsBeforeProtection = 3;
    }

    /**
     * @param $roleList array An array of roles to check for access.
     * @param $url      string The URL of the page the user is trying to access.
     *
     * @return bool $granted A Boolean value reflecting whether or not the user is allowed to access $url.
     */
    public function verifyAccess($roleList, $url)
    {
        $granted = false;
        foreach ($roleList as $role) {
            if (array_key_exists($role, $this->roles)) {
                $obj = $this->roles[$role];
                /** @var $obj BouncerRole */
                $response = $obj->verifyAccess($url);
                if ($response->getIsOverridden()) { // If access to the page is overridden return false
                    return false; // because any override is sufficient to remove permission.
                }
                if ($response->getIsAccessible()) { // If this particular role contains access to the page set granted to true
                    $granted = true; // We don't return yet in case another role overrides.
                }
            }
        }

        return $granted;
    }

    /**
     * @param string $name
     * @param array $pages
     * @param array $replaces
     */
    public function addRole($name, $pages, $replaces = NULL)
    {
        $role = new BouncerRole($name, $pages, $replaces);
        $this->roles[$name] = $role;
    }

    /**
     * @param string $url
     * @param array $params
     */
    protected function redirect($url, $params = array())
    {
        $redirects = $this->getRedirectCount();

        // Check if too many redirects have occurred
        if ($redirects >= $this->maxRedirectsBeforeProtection) {
            if ($this->redirectProtectionMethod == BouncerProtectionMethod::Session) {
                $_SESSION[$this->redirectProtectionVar] = 0;
            }
            /** @TODO: This error message is ugly when printed to the screen. Let's add the ability to grab
             *       the contents of a static html file and output that instead of an ugly error.
             *
             */
            die("Severe Error: Misconfigured roles - Maximum number of redirects reached\n");
        }

        // If we get here, we can redirect the user, just add 1 to the redirect count
        $redirects += 1;
        if ($this->redirectProtectionMethod == BouncerProtectionMethod::Session) {
            $_SESSION[$this->redirectProtectionVar] = $redirects;
        }
        if ($this->redirectProtectionMethod == BouncerProtectionMethod::Get) {
            $params[$this->redirectProtectionVar] = $redirects;
        }

        $query_string = http_build_query($params);
        $locationString = "Location: " . $url . (!empty($query_string) ? ('?' . $query_string) : '');

        header($locationString);
        exit(); // Probably also want to kill the script here
    }

    /**
     * @return int
     */
    protected function getRedirectCount()
    {
        if ($this->redirectProtectionMethod == BouncerProtectionMethod::None) {
            return 0; // Redirect protection is off, always return 0.
        }
        if ($this->redirectProtectionMethod == BouncerProtectionMethod::Session) {
            if (!isset($_SESSION[$this->redirectProtectionVar])) {
                $_SESSION[$this->redirectProtectionVar] = 0;
            }

            return $_SESSION[$this->redirectProtectionVar];
        } else {
            if (!isset($_GET[$this->redirectProtectionVar])) {
                return 0;
            }

            return $_GET[$this->redirectProtectionVar];
        }
    }

    /**
     * @param array $roleList
     * @param string $url
     * @param string $failPage
     */
    public function manageAccess($roleList, $url, $failPage = "index.php")
    {
        $granted = false;
        foreach ($roleList as $role) {
            if (array_key_exists($role, $this->roles)) {
                $obj = $this->roles[$role];
                /** @var $obj BouncerRole */
                $response = $obj->verifyAccess($url);
                if ($response->getIsOverridden()) { // If access to the page is overridden forward the user to the overriding page
                    $loc = ($obj->getOverridingPage($url) !== false) ? $obj->getOverridingPage($url) : $failPage;
                    $this->redirect($loc, array());
                }
                if ($response->getIsAccessible()) { // If this particular role contains access to the page set granted to true
                    $granted = true; // We don't return yet in case another role overrides.
                }
            }
        }
        // If we are here, we know that the page has not been overridden
        // so let's check to see if access has been granted by any of our roles.
        // If not, the user doesn't have access so we'll forward them on to the failure page.
        if (!$granted) {
            $this->redirect($failPage, array("roles" => $roleList, "url" => $url));
        } else {
            if ($this->redirectProtectionMethod == BouncerProtectionMethod::Session) {
                $_SESSION[$this->redirectProtectionVar] = 0;
            }
        }
    }

    /**
     * @param string $hostname
     * @param string $username
     * @param string $password
     * @param string $schema
     *
     * @param string $dbtype
     *
     * @throws Exception
     * @internal param string $query
     * @return boolean
     */
    public function readRolesFromDatabase($hostname = "", $username = "", $password = "", $schema = "", $dbtype = "mysql")
    {
        $dsn = NULL;
        $db = NULL;
        /* @var $db PDO * */
        switch ($dbtype) {
            case "mysql":
                // $dsn is the Data Source Name that contains info required to connect to the database
                $dsn = $dbtype . ":dbname=" . $schema . ";host=" . $hostname;
                try {
                    // we put our actual connect attempt in a try block
                    $db = new PDO($dsn, $username, $password);
                } catch (PDOException $e) {
                    // throw an exception if we don't connect
                    throw new Exception("Error connecting to MySQL!: " . $e->getMessage());
                }
                break;
            case "oci":
                $dsn = $dbtype . ":host=" . $hostname . ";dbname=" . $schema;
                try {
                    $db = new PDO($dsn, $username, $password);
                } catch (PDOException $e) {
                    throw new Exception("Error connecting to Oracle!: " . $e->getMessage());
                }
                break;
            case "sqlsrv":
                $dsn = $dbtype . ":Server=" . $hostname . ";Database=" . $schema;
                try {
                    $db = new PDO($dsn, $username, $password);
                } catch (PDOException $e) {
                    throw new Exception("Error connecting to SQL Server!: " . $e->getMessage());
                }
                break;
            default:
                throw new Exception("I don't know that database!");
                break;
        }
        // here we prepare a statement for execution.  PDO::prepare() returns a PDOStatement object
        $query = $db->prepare("call GetBouncerRoles()");
        // PDOStatement::execute() returns T/F, so we can use it in an if statement
        /* @var $query PDOStatement * */
        if ($query->execute()) {
            // PDOStatement::fetch() returns false when there are no more rows to return.  In this case,
            //      we are fetching results as an associative array, indexes are column names
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                try {
                    $name = $row["RoleName"];
                    $pages = explode("|", $row["ProvidedPages"]);
                    $overrides = array();
                    $overridesArray = explode("|", $row["OverriddenPages"]);
                    foreach ($overridesArray as $item) {
                        if (!empty($item)) {
                            $temp = explode("&", $item);
                            $overrides[$temp[0]] = $temp[1];
                        }
                    }
                    if (!empty($overrides)) {
                        $this->addRole($name, $pages, $overrides);
                    } else {
                        $this->addRole($name, $pages);
                    }
                } catch (Exception $e) {

                }
            }
        } else {
            // The query failed, Throw an error and let the programmer handle it however they want.
            throw new ErrorException("An error has occurred while attempting to fetch your roles.");
        }

        return true;
    }

    /**
     * @throws Exception
     */
    private function throwNotImplementedException()
    {
        throw new Exception("This method has not been implemented yet.");
    }

    /**
     * @param  $maxRedirectsBeforeProtection int
     */
    public function setMaxRedirectsBeforeProtection($maxRedirectsBeforeProtection)
    {
        $this->maxRedirectsBeforeProtection = $maxRedirectsBeforeProtection;
    }

    /**
     * @param  $redirectProtectionMethod int
     */
    public function setRedirectProtectionMethod($redirectProtectionMethod)
    {
        if ($redirectProtectionMethod == BouncerProtectionMethod::Session) {
            $this->redirectProtectionMethod = BouncerProtectionMethod::Session;

            return true;
        }
        if ($redirectProtectionMethod == BouncerProtectionMethod::Get) {
            $this->redirectProtectionMethod = BouncerProtectionMethod::Get;

            return true;
        }
        if ($redirectProtectionMethod == BouncerProtectionMethod::None) {
            $this->redirectProtectionMethod = BouncerProtectionMethod::None;

            return true;
        }

        return false;
    }

    /**
     * @param  $redirectProtectionVar string
     */
    public function setRedirectProtectionVar($redirectProtectionVar)
    {
        $this->redirectProtectionVar = $redirectProtectionVar;
    }

    /**
     * @return string
     */
    public function getRedirectProtectionVar()
    {
        return $this->redirectProtectionVar;
    }

}
