<?php

namespace Ronanchilvers\Sessions\Storage;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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
     * This method returns the current session state or an empty array
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return array
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function initialise(ServerRequestInterface $request): array;

    /**
     * Shut down the storage handler
     *
     * @param array $data
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function shutdown(array $data, ResponseInterface $response): ResponseInterface;
}
