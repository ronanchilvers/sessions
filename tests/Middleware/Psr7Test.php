<?php

namespace Ronanchilvers\Sessions\Test;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ronanchilvers\Sessions\Middleware\Psr7;
use Ronanchilvers\Sessions\Session;
use Ronanchilvers\Sessions\Test\TestCase;

/**
 * Base test case for PSR-7 middleware class
 *
 * @group middleware
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class Psr7Test extends TestCase
{
    /**
     * Test that invoking the middleware runs the $next closure
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testNextClosureIsInvokedByMiddleware()
    {
        $middleware = new Psr7($this->mockSession());
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

        $middleware = new Psr7($session);
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

        $middleware = new Psr7($session);
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
