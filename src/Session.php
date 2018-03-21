<?php

namespace Ronanchilvers\Sessions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ronanchilvers\Sessions\Storage\StorageInterface;

/**
 * Session helper class
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class Session
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var Ronanchilvers\Sessions\StorageInterface
     */
    protected $storage;

    /**
     * Class constructor
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Initialise the session
     *
     * @param Psr\Http\Message\ServerRequestInterface $request
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function initialise(ServerRequestInterface $request)
    {
        $this->data = $this->storage->initialise($request);
    }

    /**
     * Shutdown the session
     *
     * @param Psr\Http\Message\ResponseInterface $response
     * @return Psr\Http\Message\ResponseInterface
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function shutdown(ResponseInterface $response)
    {
        return $this->storage->shutdown($this->data, $response);
    }

    /**
     * Set a session variable
     *
     * @param string $key
     * @param mixed $value
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Get a session value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function get($key, $default = null)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        return $default;
    }

    /**
     * Does the session have a key?
     *
     * @param string $key
     * @return boolean
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }
}
