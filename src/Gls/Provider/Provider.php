<?php

/**
 * This file is part of the gls-unixbox-delivery.
 * (c) Pierre Tomasina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plab\GlsUniboxDelivery\Gls\Provider;
use Plab\GlsUniboxDelivery\Gls\Parameter\Parameter;
use Plab\GlsUniboxDelivery\Gls\Request\Request;

/**
 * Class Provider are in charge to exchange with the GLS Server
 * @package Plab\GlsUniboxDelivery\Gls\Request
 */
class Provider
{
    const GLS_WS_URL = 'http://www.gls-france.com/cgi-bin/glsboxGI.cgi';
    const GLS_WS_URL_TEST = 'http://www.gls-france.com/cgi-bin/glsboxGITest.cgi';

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var int
     */
    protected $incrementPackage;

    /**
     * @var Parameter
     */
    protected $origin;

    /**
     * @var string
     */
    protected $message;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->incrementPackage();
    }

    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function incrementPackage() :int 
    {
        if (null === $this->incrementPackage) {
            $this->incrementPackage = 0;
        }

        return $this->incrementPackage++;
    }

    /**
     * Build unique numeric increment package based on date
     * @note must be max 10 chars length and only numeric
     */
    public function getIncrementPackageNumber()
    {
        list($year, $month, $day, $hour, $minute, $second) 
            = explode('.', (new \DateTimeImmutable())->format('Y.m.d.H.i.s'));
        
        $start = (string)(
              (int)$year
            + (int)$month
            + (int)$day
            + (int)$hour
            + (int)$minute
            + (int)$second
        );

        $padLen = 14 - strlen($start);
        
        return
            $start
            .
            str_pad((string)$this->incrementPackage, $padLen, '0', STR_PAD_LEFT)
        ;
    }

    /**
     * Build the origin parameter
     * @note BP IncrementPackage ConstantZero Country
     *       02  0000000080      0000         FR
     */
    public function origin() :Parameter
    {
        if (null === $this->request) {
            throw new ProviderException('You must have request object for obtain build parameter');
        }
        
        $parameterStack            = $this->request->getParametersStack();
        $parameterProductCode      = $parameterStack->offsetGet('PRODUCT_CODE');
        $parameterRecipientCountry = $parameterStack->offsetGet('T100');

        $origin =
            $parameterProductCode->value->numeric
            . $this->getIncrementPackageNumber()
            . '0000'
            . $parameterRecipientCountry->value
        ;

        return $this->origin = new Parameter('T8975', $origin);
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Request|null $request
     * @param bool $run if we must run the request or not
     * @throws ProviderException
     */
    public function prepare(Request $request = null, $run = false)
    {
        if (null !== $request) {
            $this->setRequest($request);
        }

        if (null === $this->request) {
            throw new ProviderException('You must specify request argument or call prepare method before');
        }

        $this->request
            ->createParameterInStack('T082', Parameter::T082)
            ->createParameterInStack('T090', Parameter::T090)

            ->createParameterInStack('T8973', '1')
            ->createParameterInStack('T8904', '1')
            ->createParameterInStack('T8702', '1')
            ->createParameterInStack('T8905', '1')
            
            ->addToParametersStack($this->config->shippingDate)
            ->addToParametersStack($this->config->contactId)
            ->addToParametersStack($this->config->clientCode)
            ->addToParametersStack($this->config->glsWarehouse)
            ->addToParametersStack($this->config->senderName)
            ->addToParametersStack($this->config->senderAddress)
            ->addToParametersStack($this->config->senderCountry)
            ->addToParametersStack($this->config->senderZip)
            ->addToParametersStack($this->config->senderCity)

            ->addToParametersStack($this->origin())
        ;

        $this->message = $this->request->formatMessage();

        if (true === $run) {
            $this->run();
        }
    }

    public function getWsUrl()
    {
        if (defined('__DEV_MODE__')) {
            return static::GLS_WS_URL_TEST;
        }

        return static::GLS_WS_URL;
    }

    public function run()
    {
        $ch = curl_init();

        $headers = [
            "POST ".$page." HTTP/1.1",
            "Content-type: text/plain;charset=\"utf-8\"",
            "Accept: text/plain",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "Content-length: ".strlen($this->message),
        ];

        curl_setopt($ch, CURLOPT_URL, $this->getWsUrl());
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->message);

        $result      = curl_exec($ch);
        $errNo       = curl_errno($ch);
        $errMessages = curl_error($ch);

        curl_close($ch);
        
        if (0 < $errNo) {
            throw new ProviderWsTimeoutException($errMessages);
        }
        
        $responseClass = $this->request->getResponseClass();
        return new $responseClass($result);
    }
}