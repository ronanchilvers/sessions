<?php

namespace Ronanchilvers\Sessions\Test;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ronanchilvers\Sessions\Middleware\Psr15;
use Ronanchilvers\Sessions\Session;
use Ronanchilvers\Sessions\Test\TestCase;

/**
 * Base test case for PSR-15 middleware class
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class Psr15Test extends TestCase
{
    /**
     * Get a mock Psr\Http\Server\RequestHandlerInterface object
     *
     * @group middleware
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function mockRequestHandler(): RequestHandlerInterface
    {
        return $this->createMock(RequestHandlerInterface::class);
    }

    /**
     * Test that initialise is called on the session object
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testInitialiseIsCalledOnTheSession()
    {
        $response = $this->mockResponse();
        $handler = $this->mockRequestHandler();
        $handler->expects($this->once())
                ->method('handle')
                ->willReturn($response);
        $request = $this->mockRequest();
        $session = $this->mockSession();
        $session->expects($this->any())
                ->method('shutdown')
                ->willReturn($response);
        $session->expects($this->once())
                ->method('initialise');

        $middleware = new Psr15($session);

        $result = $middleware->process(
            $request,
            $handler
        );

        $this->assertSame($response, $result);
    }

    /**
     * Test that shutdown is called on the session object
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testShutdownIsCalledOnTheSession()
    {
        $response = $this->mockResponse();
        $handler = $this->mockRequestHandler();
        $handler->expects($this->once())
                ->method('handle')
                ->willReturn($response);
        $request = $this->mockRequest();
        $session = $this->mockSession();
        $session->expects($this->once())
                ->method('shutdown')
                ->willReturn($response);

        $middleware = new Psr15($session);

        $result = $middleware->process(
            $request,
            $handler
        );

        $this->assertSame($response, $result);
    }
}
