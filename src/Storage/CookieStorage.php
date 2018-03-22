<?php

namespace Ronanchilvers\Sessions\Storage;

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
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
     * Class constructor
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function __construct($settings = [])
    {
        $defaults = [
            'name'     => 'app_session',
            'expire'   => 0,
            'path'     => '/',
            'domain'   => null,
            'secure'   => false,
            'httponly' => true,
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
        if (!is_null($data)) {
            $data = @unserialize($data);
        }
        if (!is_array($data)) {
            $data = [];
        }

        return $data;
    }

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function shutdown(array $data, ResponseInterface $response)
    {
        $data = serialize($data);
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
}
