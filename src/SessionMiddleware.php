<?php

namespace Ronanchilvers\Sessions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ronanchilvers\Sessions\Session;

/**
 * Middleware to initialise sessions
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class SessionMiddleware
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
        $this->session->shutdown($response);

        return $response;
    }
}
