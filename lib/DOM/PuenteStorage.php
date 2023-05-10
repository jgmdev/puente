<?php
/*
 * @author Jefferson González
 * @license MIT
 * @link https://github.com/jgmdev/puente Source code.
 */

namespace Puente\DOM;

/**
 * Gives access to the Puente storage array so you can store/read variables
 * like intervals, windows, etc... when needed.
 */
class PuenteStorage extends ADomObject
{
    private $instance = 0;

    /**
     * Constructor.
     *
     * @param \Puente\Puente $owner
     */
    public function __construct(\Puente\Puente $owner)
    {
        $this->instance = $owner->getInstanceID();

        parent::__construct("Puente{$this->instance}", $owner);
    }

    /**
     * Inserts a variable to the Puente array storage.
     *
     * @param string $name
     * @param string $value
     *
     * @return \Puente\DOM\PuenteStorage
     */
    public function insertVar(string $name): self
    {
        $this->owner->addCode(
            "Puente{$this->instance}['$name'] = $name;"
        );

        return $this;
    }

    /**
     * Removes a variable from the Puente array storage.
     *
     * @param string $name
     * @param string $value
     *
     * @return \Puente\DOM\PuenteStorage
     */
    public function removeVar(string $name): self
    {
        $this->owner->addCode(
            "delete Puente{$this->instance}['$name'];"
        );

        return $this;
    }

    /**
     * Gets the Puente instance with the variable name as index which
     * is useful in different scenarios, lets say you stored a setInterval
     * object and want to clear it out with clearInterval, eg:
     *     clearInvertal($storage->getVarName("myInterval"))
     * would translate to:
     *     clearInterval(Puente1['myInterval'])
     *
     * @param string $name
     *
     * @return string
     */
    public function getVarInstance(string $name): string
    {
        return "Puente{$this->instance}['$name']";
    }

    /**
     * Remove all variables.
     *
     * @return \Puente\DOM\PuenteStorage
     */
    public function clear(): self
    {
        $this->owner->addCode(
            "Puente{$this->instance} = [];"
        );

        return $this;
    }
}
