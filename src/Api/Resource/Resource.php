<?php
/**
 * This file is part of the gls-unixbox-delivery.
 * (c) 2016 Pierre Tomasina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plab\GlsUniboxDelivery\Api\Resource;
use Plab\GlsUniboxDelivery\Api\Api;
use Plab\GlsUniboxDelivery\Gls\Provider\Provider;

/**
 * Class Resource
 * @package Plab\GlsUniboxDelivery\Api\Resource
 */
abstract class Resource
{
    /**
     * @var Provider
     */
    protected $provider;
    protected $body;

    abstract public function get();
    abstract public function post();
    abstract public function put();
    abstract public function update();
    abstract public function delete();
    abstract public function head();

    /**
     * Resource constructor.
     * @param Provider $provider
     */
    public function __construct(Provider $provider) 
    {
        $this->setProvider($provider);
        $this->body = file_get_contents('php://input');
    }

    /**
     * @param Provider $provider
     */
    public function setProvider(Provider $provider) 
    {
        $this->provider = $provider;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function bodyAsJson()
    {
        $json = json_decode($this->body);
        
        if (null === $json) {
            Api::httpError(406);
        }
        
        return $json;
    }
    
    public function result(string $type, $body)
    {
        if ('json' === $type) {
            header('Content-Type: application/json; charset=utf-8');
            $body = json_encode($body);
        }
        
        echo $body;
    }
}