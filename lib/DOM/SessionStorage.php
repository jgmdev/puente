<?php
/**
 * @author Jefferson González
 * @license MIT
 * @link https://github.com/jgmdev/puente Source code.
 */

namespace Puente\DOM;

/**
 * Representation of the sessionStorage DOM object which lets you save
 * data inside the user browser. The difference with localStorage is that
 * sessionStorage stores data for the life cycle of the opened window. Once
 * the window is closed the data is destroyed.
 */
class SessionStorage extends LocalStorage
{   
    /**
     * Constructor.
     *
     * @param \Puente\Puente $owner
     */
    public function __construct(\Puente\Puente $owner)
    {
        $this->name = "sessionStorage";
        $this->owner = $owner;
    }
}