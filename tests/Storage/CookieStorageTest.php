<?php

namespace Ronanchilvers\Sessions\Test\Storage;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\BadFormatException;
use Defuse\Crypto\Key;
use Ronanchilvers\Sessions\Storage\CookieStorage;
use Ronanchilvers\Sessions\Test\TestCase;

/**
 * Test case for cookie based session storage
 *
 * @group storage
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class CookieStorageTest extends TestCase
{
    /**
     * @var string
     */
    protected $keyString = 'def000009754a6a56f40c918d713724912c7395b27645f74c745f010ca1f43d68e6c859f1e8a92c4b1ec4f408daa39cdad7faae98407464c5cf46404863f8fa97b903637';

    /**
     * @var Defuse\Crypto\Key
     */
    protected $key;

    protected $cookieString = 'app_session={crypt}; Path=/; HttpOnly';

    /**
     * Setup the $_SESSION super global
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function setUp(): void
    {
        $this->key = Key::loadFromAsciiSafeString($this->keyString);
    }

    /**
     * Test that initialise returns the current session data
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testInitialiseReturnsCurrentSessionData()
    {
        $data = ['foo' => 'bar'];
        $cookieString = str_replace(
            '{crypt}',
            $this->encrypt($data),
            $this->cookieString
        );
        $request = $this->mockRequest();
        $request->expects($this->once())
                ->method('getHeaderLine')
                ->with($this->equalTo('Cookie'))
                ->willReturn($cookieString);
        $storage = new CookieStorage([
            'encryption.key' => $this->keyString
        ]);

        $return = $storage->initialise($request);

        $this->assertEquals(['foo' => 'bar'], $return);
    }

    /**
     * Test that an invalid key throws exception
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testInvalidKeyThrowsException()
    {
        $data = ['foo' => 'bar'];
        $cookieString = str_replace(
            '{crypt}',
            $this->encrypt($data),
            $this->cookieString
        );
        $request = $this->mockRequest();
        $request->expects($this->once())
                ->method('getHeaderLine')
                ->with($this->equalTo('Cookie'))
                ->willReturn($cookieString);
        $storage = new CookieStorage([
            'encryption.key' => false
        ]);

        $this->expectException(BadFormatException::class);
        $return = $storage->initialise($request);
    }

    /**
     * Test that corrupted cookies kill the session
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testCorruptedCookieKillsSession()
    {
        $data = ['foo' => 'bar'];
        $cookieString = str_replace(
            '{crypt}',
            $this->encrypt($data) . 'corrupt',
            $this->cookieString
        );
        $request = $this->mockRequest();
        $request->expects($this->once())
                ->method('getHeaderLine')
                ->with($this->equalTo('Cookie'))
                ->willReturn($cookieString);
        $storage = new CookieStorage([
            'encryption.key' => $this->keyString
        ]);

        $return = $storage->initialise($request);

        $this->assertEquals([], $return);
    }

    /**
     * Test that shutdown sets the session data
     *
     * @test
     * @group foo
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testShutdownSetsTheSessionData()
    {
        $data = ['foo' => 'bar'];
        $response = $this->mockResponse();
        $response->expects($this->once())
                 ->method('getHeader')
                 ->with(
                    'Set-Cookie'
                 )
                 ->willReturn([]);
        $response->expects($this->once())
                 ->method('withoutHeader')
                 ->with(
                    'Set-Cookie'
                )
                ->willReturn($response)
                ;
        $spyValue = '';
        $response->expects($this->once())
                 ->method('withAddedHeader')
                 ->will(
                    $this->returnCallback(function ($name, $value) use ($spy, $response) {
                        $spyValue = $value;

                        return $response;
                    })
                 );
        $storage = new CookieStorage([
            'encryption.key' => $this->keyString
        ]);
        $response = $storage->shutdown($data, $response);
        // $cookieString = $spy->getInvocations()[0]->getParameters()[1];
        $cookieString = explode(';', $spy);
        $cookieString = explode('=', $cookieString[0]);
        // @TODO Remove var_dump
        var_dump($cookieString); exit();
        $return = $this->decrypt($cookieString[1]);

        $this->assertEquals($return, $data);
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
        $response->expects($this->once())
                 ->method('getHeader')
                 ->with(
                    'Set-Cookie'
                 )
                 ->willReturn([]);
        $response->expects($this->once())
                 ->method('withoutHeader')
                 ->with(
                    'Set-Cookie'
                )
                ->willReturn($response)
                ;
        $response->expects($this->once())
                 ->method('withAddedHeader')
                ->willReturn($response)
                ;
        $storage = new CookieStorage([
            'encryption.key' => $this->keyString
        ]);
        $return = $storage->shutdown([], $response);
        $this->assertEquals($response, $return);
    }

    /**
     * Encrypt an array
     *
     * @param array $data
     * @return string
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function encrypt($data)
    {
        $data = serialize($data);
        return Crypto::encrypt(
            $data,
            $this->key
        );
    }

    /**
     * Decrypt a string
     *
     * @param string $data
     * @return array
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function decrypt($data): array
    {
        $data = Crypto::decrypt(
            $data,
            $this->key
        );

        return unserialize($data);
    }
}
