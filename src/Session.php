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
     * @var \Ronanchilvers\Sessions\Storage\StorageInterface
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
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function initialise(ServerRequestInterface $request)
    {
        $this->data = $this->storage->initialise($request);
    }

    /**
     * Shutdown the session
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
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
     * Delete a session key
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function delete($key)
    {
        if ($this->has($key)) {
            unset($this->data[$key]);
        }
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

    /**
     * Set a flash message
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function flash($message, $type = 'info')
    {
        if (!isset($this->data['flash'])) {
            $this->data['flash'] = [];
        }
        if (!isset($this->data['flash'][$type])) {
            $this->data['flash'][$type] = [];
        }
        $this->data['flash'][$type][] = $message;
    }

    /**
     * Get a set of flash messages for a given type
     *
     * @param string $type
     * @return mixed
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function getFlash($type)
    {
        if (!isset($this->data['flash'], $this->data['flash'][$type])) {
            return null;
        }
        if (empty($this->data['flash'][$type])) {
            return null;
        }
        $messages = $this->data['flash'][$type];
        unset($this->data['flash'][$type]);

        return $messages;
    }

    /**
     * Get all flash messages
     *
     * @return array
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function getFlashes()
    {
        if (!isset($this->data['flash'])) {
            return null;
        }
        $flashes = $this->data['flash'];
        unset($this->data['flash']);

        return $flashes;
    }
}
