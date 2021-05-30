<?php

namespace Ronanchilvers\Sessions\Test;

use Ronanchilvers\Sessions\Session;
use Ronanchilvers\Sessions\Storage\StorageInterface;
use Ronanchilvers\Sessions\Test\TestCase;

/**
 * Base test case for session class
 *
 * @group session
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class SessionTest extends TestCase
{
    /**
     * Get a mock session storage object
     *
     * @return Ronanchilvers\Sessions\Storage\StorageInterface
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function mockStorage()
    {
        $storage = $this->createMock(StorageInterface::class);

        return $storage;
    }

    /**
     * Get a new session object to test
     *
     * @return Ronanchilvers\Sessions\Session
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function newSession()
    {
        $storage = $this->mockStorage();
        $session = new Session($storage);

        return $session;
    }

    /**
     * Provider for session data
     *
     * @return array
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function sessionDataProvider()
    {
        return [
            ['foo', 'bar'],
            ['array', ['foo' => 'bar']],
            ['boolean', true]
        ];
    }

    /**
     * Test that a key can be set
     *
     * @dataProvider sessionDataProvider
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testDataValuesCanBeSetAndRetrieved($key, $value)
    {
        $session = $this->newSession();
        $session->set($key, $value);

        $this->assertEquals($value, $session->get($key));
    }

    /**
     * Test that getting an unknown value with a specified default returns the default
     *
     * @dataProvider sessionDataProvider
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testUnknownKeyWithSetDefaultReturnsDefault($key, $value)
    {
        $session = $this->newSession();

        $this->assertEquals($value, $session->get($key, $value));
    }

    /**
     * Test that getting an unknown key returns null by default
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testUnknownKeyReturnsNull()
    {
        $session = $this->newSession();
        $this->assertNull($session->get('unknown'));
    }

    /**
     * Test that keys can be deleted
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testKeysCanBeDeleted()
    {
        $session = $this->newSession();

        $session->set('foo', 'bar');
        $this->assertEquals('bar', $session->get('foo'));

        $session->delete('foo');
        $this->assertNull($session->get('foo'));
    }

    /**
     * Test that checking presence of an unknown key returns false
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testPresenceOfUnknownKeyReturnsFalse()
    {
        $session = $this->newSession();

        $this->assertFalse($session->has('unknown'));
    }

    /**
     * Test that checking presence of a known key returns true
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testPresenceOfKnownKeyReturnsTrue()
    {
        $session = $this->newSession();
        $session->set('known', 'foobar');

        $this->assertTrue($session->has('known'));
    }

    /**
     * Test that initialising from storage sets the session data
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testInitialiseSetsSessionData()
    {
        $request = $this->mockRequest();
        $storage = $this->mockStorage();
        $storage->expects($this->once())
                ->method('initialise')
                ->with($this->equalTo($request))
                ->willReturn(['foo' => 'bar']);

        $session = new Session($storage);
        $session->initialise($request);

        $this->assertEquals('bar', $session->get('foo'));
    }

    /**
     * Test that shutdown returns the response object
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testShutdownReturnsResponseObject()
    {
        $response = $this->mockResponse();
        $storage = $this->mockStorage();
        $storage->expects($this->once())
                ->method('shutdown')
                ->with(
                    $this->equalTo(['foo' => 'bar']),
                    $this->equalTo($response)
                )
                ->willReturn($response);

        $session = new Session($storage);
        $session->set('foo', 'bar');

        $this->assertEquals($response, $session->shutdown($response));
    }
}
