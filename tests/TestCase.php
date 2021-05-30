<?php

namespace Ronanchilvers\Sessions\Test;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ronanchilvers\Sessions\Session;

/**
 * Base test case for session middleware class
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * Get a mock PSR7 request object
     *
     * @return Psr\Http\Message\ServerRequestInterface
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function mockRequest()
    {
        return $this->createMock(ServerRequestInterface::class);
    }

    /**
     * Get a mock response object
     *
     * @return Psr\Http\Message\ResponseInterface
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function mockResponse()
    {
        return $this->createMock(ResponseInterface::class);
    }

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
}
