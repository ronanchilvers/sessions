<?php

namespace Ronanchilvers\Sessions;

/**
 * Interface for session storage handlers
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
interface StorageInterface
{
    /**
     * Initialise the storage handler
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function initialise();

    /**
     * Shut down the storage handler
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function shutdown();
}
