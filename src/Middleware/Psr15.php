<?php

namespace Ronanchilvers\Sessions\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ronanchilvers\Sessions\Session;

/**
 * PSR-15 Middleware to initialise sessions
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class Psr15 implements MiddlewareInterface
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
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->session->initialise($request);
        $response = $handler->handle($request);
        $response = $this->session->shutdown($response);

        return $response;
    }
}
