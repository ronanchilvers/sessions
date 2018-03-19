<?php

namespace Ronanchilvers\Sessions;

use Ronanchilvers\Sessions\StorageInterface;

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
    protected $data;

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
}

