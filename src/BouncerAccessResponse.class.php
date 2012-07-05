<?php
/**
 * Created with JetBrains PhpStorm.
 * User: Brendon Dugan <wishingforayer@gmail.com>
 * Date: 7/4/12
 * Time: 9:44 PM
 *
 */
class BouncerAccessResponse
{
    private $isAccessible;
    private $isOverridden;

    /**
     *
     */
    public function __construct()
    {
        $this->isAccessible = false;
        $this->isOverridden = false;
    }

    /**
     * @param bool $isAccessible
     */
    public function setIsAccessible($isAccessible)
    {
        if (is_bool($isAccessible))
            $this->isAccessible = $isAccessible;
    }

    /**
     * @return bool
     */
    public function getIsAccessible()
    {
        return $this->isAccessible;
    }

    /**
     * @param bool $isOverridden
     */
    public function setIsOverridden($isOverridden)
    {
        if (is_bool($isOverridden))
            $this->isOverridden = $isOverridden;
    }

    /**
     * @return bool
     */
    public function getIsOverridden()
    {
        return $this->isOverridden;
    }
}
