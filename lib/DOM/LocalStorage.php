<?php
/**
 * @author Jefferson González
 * @license MIT
 * @link https://github.com/jgmdev/puente Source code.
 */

namespace Puente\DOM;

/**
 * Representation of the localStorage DOM object which lets you save
 * data inside the user browser.
 */
class LocalStorage extends ADomObject
{   
    /**
     * Constructor.
     *
     * @param \Puente\Puente $owner
     */
    public function __construct(\Puente\Puente $owner)
    {
        parent::__construct("localStorage", $owner);
    }

    /**
     * Set the value of the specified local storage item.
     *
     * @param string $name
     * @param string $value
     * 
     * @return \Puente\DOM\LocalStorage
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
     * @return \Puente\DOM\LocalStorage
     */
    public function removeItem(string $name): self
    {
        $this->callMethod("removeItem", $name);
        
        return $this;
    }

    /**
     * Remove all local storage items.
     * 
     * @return \Puente\DOM\LocalStorage
     */
    public function clear(): self
    {
        $this->callMethod("clear");
        
        return $this;
    }
}