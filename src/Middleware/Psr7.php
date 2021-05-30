<?php

namespace Ronanchilvers\Sessions\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ronanchilvers\Sessions\Session;

/**
 * PSR-7 Middleware to initialise sessions
 *
 * Note this middleware format is deprecated. It is used by frameworks such as
 * Slim3 but newer implementations should probably use the PSR-15 equivalent.
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class Psr7
{
    /**
     * @var Ronanchilvers\Sessions\Session
     */
    protected $session;

    /**
     * Class constructor
     *
     * @param Ronanchilvers\Sessions\Session $session
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Invoke the middleware
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $this->session->initialise($request);
        $response = $next($request, $response);
        $response = $this->session->shutdown($response);

        return $response;
    }
}
