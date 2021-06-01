<?php

namespace Ronanchilvers\Sessions\Test\Storage;

use Ronanchilvers\Sessions\Storage\NativeStorage;
use Ronanchilvers\Sessions\Test\TestCase;

/**
 * Test case for native session storage
 *
 * @group storage
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class NativeStorageTest extends TestCase
{
    /**
     * Setup the $_SESSION super global
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function setUp(): void
    {
        $_SESSION = [];
    }

    /**
     * Test that initialise returns the current session data
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testInitialiseReturnsCurrentSessionData()
    {
        $request = $this->mockRequest();
        $storage = new NativeStorage();

        $data = $_SESSION;
        $return = @$storage->initialise($request); // Silence cookie warnings

        $this->assertEquals($data, $return);
    }

    /**
     * Test that shutdown sets the session data
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testShutdownSetsTheSessionData()
    {
        $data = $_SESSION;
        $response = $this->mockResponse();
        $storage = new NativeStorage();
        $return = $storage->shutdown($data, $response);
        $this->assertEquals($_SESSION, $data);
    }

    /**
     * Test that shutdown returns the response object
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testShutdownReturnsTheResponseObject()
    {
        $response = $this->mockResponse();
        $storage = new NativeStorage();
        $return = $storage->shutdown([], $response);
        $this->assertEquals($response, $return);
    }

}
