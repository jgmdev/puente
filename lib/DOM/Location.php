<?php
/*
 * @author Jefferson González
 * @license MIT
 * @link https://github.com/jgmdev/puente Source code.
 */

namespace Puente\DOM;

/**
 * Representation of the location DOM object.
 */
class Location extends ADomObject
{
    /**
     * Constructor.
     *
     * @param \Puente\Puente $owner
     * @param string $name Default is location but you may override this
     * to get the location object of a specific element eg: myWindow.location
     */
    public function __construct(\Puente\Puente $owner, string $name="location")
    {
        parent::__construct($name, $owner);
    }

    /**
     * Loads a new document. Keeps current document in browser history so it
     * is possible to navigate back to it from the user browser.
     *
     * @param string $url
     *
     * @return \Puente\DOM\Location
     */
    public function assign(string $url): self
    {
        $this->callMethod("assign", $url);

        return $this;
    }

    /**
     * Reloads the current document.
     *
     * @param bool $force_get If false (default) reloads from browser cache,
     * if true downloads a fresh copy.
     *
     * @return \Puente\DOM\Location
     */
    public function reload(bool $force_get=false): self
    {
        $this->callMethod("reload", $force_get ? "js:true" : "js:false");

        return $this;
    }

    /**
     * Replaces the current document with a new one. It removes current document
     * from browser history so it is not possible to navigate back to it from
     * the user browser.
     *
     * @param string $url
     *
     * @return \Puente\DOM\Location
     */
    public function replace(string $url): self
    {
        $this->callMethod("replace", $url);

        return $this;
    }

    /**
     * Set the anchor part of the current url (#anchor).
     *
     * @param string $value
     *
     * @return \Puente\DOM\Location
     */
    public function hash(string $value): self
    {
        $this->assignProperty("hash", $value);

        return $this;
    }

    /**
     * Set the hostname and port, eg: myhost:8080.
     *
     * @param string $value
     *
     * @return \Puente\DOM\Location
     */
    public function host(string $value): self
    {
        $this->assignProperty("host", $value);

        return $this;
    }

    /**
     * Set the hostname, eg: myhost.com.
     *
     * @param string $value
     *
     * @return \Puente\DOM\Location
     */
    public function hostname(string $value): self
    {
        $this->assignProperty("hostname", $value);

        return $this;
    }

    /**
     * Set the entire url, eg: http://mysite.com/something?stuff=stuff.
     *
     * @param string $value
     *
     * @return \Puente\DOM\Location
     */
    public function href(string $value): self
    {
        $this->assignProperty("href", $value);

        return $this;
    }

    /**
     * Set the path part of current url.
     *
     * @param string $value
     *
     * @return \Puente\DOM\Location
     */
    public function pathname(string $value): self
    {
        $this->assignProperty("pathname", $value);

        return $this;
    }

    /**
     * Set the port of current url.
     *
     * @param string $value
     *
     * @return \Puente\DOM\Location
     */
    public function port(string $value): self
    {
        $this->assignProperty("port", $value);

        return $this;
    }

    /**
     * Set the protocol part of the url. Posible values can be:
     * file:, ftp:, http:, https: and mailto:
     *
     * @param string $value
     *
     * @return \Puente\DOM\Location
     */
    public function protocol(string $value): self
    {
        $this->assignProperty("protocol", $value);

        return $this;
    }

    /**
     * Set the query string part of the url, eg: ?myvar=value
     *
     * @param string $value
     *
     * @return \Puente\DOM\Location
     */
    public function search(string $value): self
    {
        $this->assignProperty("search", $value);

        return $this;
    }
}
