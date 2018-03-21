<?php

namespace Ronanchilvers\Sessions\Test\Storage;

use Ronanchilvers\Sessions\Storage\NativeStorage;
use Ronanchilvers\Sessions\Test\TestCase;

/**
 * Test case for native session storage
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class NativeStorageTest extends TestCase
{
    /**
     * Test that initialising NativeStorage starts the session
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testInitialiseStartsSession()
    {
        $request = $this->mockRequest();
        $storage = new NativeStorage();
        @$storage->initialise($request); // Silence cookie warnings

        $this->assertEquals(PHP_SESSION_ACTIVE, session_status());
    }

    /**
     * Test that initialise returns the current session data
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testInitialiseReturnsCurrentSessionData()
    {
        $data = [
            'foo' => 'bar'
        ];
        $request = $this->mockRequest();
        $storage = new NativeStorage();

        $_SESSION = $data;
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
        $data = [
            'foo' => 'bar'
        ];
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
