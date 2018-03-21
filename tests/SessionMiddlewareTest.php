<?php

namespace Ronanchilvers\Sessions\Test;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ronanchilvers\Sessions\Session;
use Ronanchilvers\Sessions\SessionMiddleware;
use Ronanchilvers\Sessions\Test\TestCase;

/**
 * Base test case for session middleware class
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class SessionMiddlewareTest extends TestCase
{
    /**
     * Get a mock session object
     *
     * @return Ronanchilvers\Sessions\Session
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function mockSession()
    {
        return $this->createMock(Session::class);
    }

    /**
     * Test that invoking the middleware runs the $next closure
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testNextClosureIsInvokedByMiddleware()
    {
        $middleware = new SessionMiddleware($this->mockSession());
        $next = function (ServerRequestInterface $request, ResponseInterface $response) {
            return $response->withStatus(200);
        };
        $request = $this->mockRequest();
        $response = $this->mockResponse();
        $response->expects($this->once())
                 ->method('withStatus')
                 ->willReturn($response);

        $middleware(
            $request,
            $response,
            $next
        );
    }

    /**
     * Test that initialise is called on the session object
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testInitialiseIsCalledOnTheSession()
    {
        $request = $this->mockRequest();
        $response = $this->mockResponse();
        $response->expects($this->once())
                 ->method('withStatus')
                 ->willReturn($response);
        $session = $this->mockSession();
        $session->expects($this->once())
                ->method('initialise')
                ->with($this->equalTo($request));

        $middleware = new SessionMiddleware($session);
        $next = function (ServerRequestInterface $request, ResponseInterface $response) {
            return $response->withStatus(200);
        };

        $middleware(
            $request,
            $response,
            $next
        );
    }

    /**
     * Test that shutdown is called on the session object
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testShutdownIsCalledOnTheSession()
    {
        $request = $this->mockRequest();
        $response = $this->mockResponse();
        $response->expects($this->once())
                 ->method('withStatus')
                 ->willReturn($response);
        $session = $this->mockSession();
        $session->expects($this->once())
                ->method('shutdown')
                ->with($this->equalTo($response));

        $middleware = new SessionMiddleware($session);
        $next = function (ServerRequestInterface $request, ResponseInterface $response) {
            return $response->withStatus(200);
        };

        $middleware(
            $request,
            $response,
            $next
        );
    }
}
