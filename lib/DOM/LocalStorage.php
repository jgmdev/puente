<?php
/**
 * @author Jefferson González
 * @license MIT
 * @link https://github.com/jgmdev/pquery Source code.
 */

namespace PQuery\DOM;

/**
 * Representation of the localStorage DOM object which lets you save
 * data inside the user browser.
 */
class LocalStorage extends ADomObject
{   
    /**
     * Constructor.
     *
     * @param \PQuery\PQuery $owner
     */
    public function __construct(\PQuery\PQuery $owner)
    {
        parent::__construct("localStorage", $owner);
    }

    /**
     * Set the value of the specified local storage item.
     *
     * @param string $name
     * @param string $value
     * 
     * @return \PQuery\DOM\LocalStorage
     */
    public function setItem(string $name, string $value): self
    {
        $this->callMethod("setItem", $name, $value);
        
        return $this;
    }

    /**
     * Removes the specified local storage item.
     *
     * @param string $name
     * 
     * @return \PQuery\DOM\LocalStorage
     */
    public function removeItem(string $name): self
    {
        $this->callMethod("removeItem", $name);
        
        return $this;
    }

    /**
     * Remove all local storage items.
     * 
     * @return \PQuery\DOM\LocalStorage
     */
    public function clear(): self
    {
        $this->callMethod("clear");
        
        return $this;
    }
}