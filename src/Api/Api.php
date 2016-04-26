<?php
/**
 * This file is part of the gls-unixbox-delivery.
 * (c) 2016 Pierre Tomasina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plab\GlsUniboxDelivery\Api;
use Plab\GlsUniboxDelivery\Api\Resource;
use Plab\GlsUniboxDelivery\Gls\Provider\Provider;

/**
 * Class Api
 * @package Plab\GlsUniboxDelivery\Api
 */
class Api
{
    /**
     * @var Provider
     */
    protected $provider;
    protected $routes;

    const HTTP_STATUS = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',

        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        210 => 'Content Different',
        226 => 'IM Used',

        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        310 => 'Too many Redirects',

        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested range unsatisfiable',
        417 => 'Expectation failed',
        418 => 'I’m a teapot',
        422 => 'Unprocessable entity',
        423 => 'Locked',
        424 => 'Method failure',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        451 => 'Unavailable For Legal Reasons',
        456 => 'Unrecoverable Error',
        499 => 'client has closed connection',

        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway ou Proxy Error',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant also negociate',
        507 => 'Insufficient storage',
        508 => 'Loop detected',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not extended',
        520 => 'Web server is returning an unknown error'
    ];

    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
        $this->setRoute('/parcels', '\Plab\GlsUniboxDelivery\Api\Resource\Parcel');
    }

    public function run()
    {
        $route  = strtolower($_SERVER['REQUEST_URI']);
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        
        if (false === array_key_exists($route, $this->routes)) {
            return self::httpError(404);
        }

        $resourceClassName = $this->routes[$route];
        
        $resource = new $resourceClassName($this->provider);
        
        try {
            $resource->$method();
        } catch (\Throwable $e) {
            
            if (defined('__DEV_MODE__')) {
                throw $e;
            }
            
            $messageCmp = 'call to undefined method';
            $message = strtolower($e->getMessage());

            if ($messageCmp === substr($message, 0, strlen($messageCmp))) {
                return self::httpError(501);
            }

            return self::httpError(500);
        } catch (\Exception $e) {

            if (defined('__DEV_MODE__')) {
                throw $e;
            }

            return self::httpError(500);
        }
    }

    protected function setRoute(string $routingPath, string $resourceClassName)
    {
        $this->routes[$routingPath] = $resourceClassName;
    }

    public static function httpError(int $httpCode)
    {
        if (false === array_key_exists($httpCode, self::HTTP_STATUS)) {
            throw new \Exception("Http code $httpCode not exist");
        }
        
        header($_SERVER['SERVER_PROTOCOL']." $httpCode " . self::HTTP_STATUS[$httpCode], null, $httpCode);

        return null;
    }
}