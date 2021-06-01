<?php

namespace Ronanchilvers\Sessions\Storage;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Defuse\Crypto\Key;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Exception;
use TypeError;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ronanchilvers\Sessions\Storage\StorageInterface;

/**
 * Cookie storage handler
 *
 * This handler uses a cookie to store session data
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class CookieStorage implements StorageInterface
{
    /**
     * @var array
     */
    protected $settings;

    /**
     * @var \Defuse\Crypto\Key|null
     */
    protected $key = null;

    /**
     * Class constructor
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function __construct($settings = [])
    {
        $defaults = [
            'name'           => 'app_session',
            'expire'         => 0,
            'path'           => '/',
            'domain'         => null,
            'secure'         => false,
            'httponly'       => true,
            'encryption.key' => null
        ];
        $this->settings = array_merge(
            $defaults,
            $settings
        );
    }

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function initialise(ServerRequestInterface $request): array
    {
        $cookie = FigRequestCookies::get(
            $request,
            $this->settings['name']
        );
        $data = $cookie->getValue();
        if (is_null($data)) {
            return [];
        }
        try {
            $data = Crypto::decrypt(
                $data,
                $this->getKey()
            );
            if (!is_null($data)) {
                $data = @unserialize($data);
            }
        } catch (WrongKeyOrModifiedCiphertextException $ex) {
            $data = null;
        }
        if (!is_array($data)) {
            $data = [];
        }

        return $data;
    }

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function shutdown(array $data, ResponseInterface $response): ResponseInterface
    {
        $data = serialize($data);
        $data = Crypto::encrypt(
            $data,
            $this->getKey()
        );
        $cookie = SetCookie::create(
            $this->settings['name'],
            $data
        );
        $cookie = $cookie->withExpires($this->settings['expire'])
                         ->withPath($this->settings['path'])
                         ->withDomain($this->settings['domain'])
                         ->withSecure($this->settings['secure'])
                         ->withHttpOnly($this->settings['httponly'])
                         ;
        $response = FigResponseCookies::set(
            $response,
            $cookie
        );

        return $response;
    }

    /**
     * Get an encryption key object instance
     *
     * @return \Defuse\Crypto\Key
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function getKey()
    {
        if (!$this->key instanceof Key) {
            $this->key = Key::loadFromAsciiSafeString($this->settings['encryption.key']);
        }
        return $this->key;
    }
}
