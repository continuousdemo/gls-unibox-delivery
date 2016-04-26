<?php
/**
 * This file is part of the gls-unixbox-delivery.
 * (c) Pierre Tomasina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plab\GlsUniboxDelivery\Gls\Request;
use Plab\GlsUniboxDelivery\Gls\Parameter\Parameter;
use Plab\GlsUniboxDelivery\Gls\StackIndex;

/**
 * Class Request | Value object
 * @package Plab\GlsUniboxDelivery\Gls\Request
 */
abstract class Request
{
    const DELIMITER = '|';
    const SEPARATOR = ':';

    protected $parameters;

    /**
     * Return the class used for parse the response of webservice
     * @return string
     */
    abstract public function getResponseClass() :string;

    public function __construct()
    {
        $this->parameters = new StackIndex();
    }

    public function formatMessage() :string
    {
        $message =
            '\\' . '\\' . '\\' . '\\' . '\\'
            . 'GLS' .
            '\\' . '\\' . '\\' . '\\' . '\\'
        ;

        foreach ($this->parameters as $parameter) {

            $message .= self::DELIMITER . $parameter->key . self::SEPARATOR . $parameter->value;
        }

        $message .= self::DELIMITER . '/////GLS/////';
        
        return $message;
    }

    /**
     * @param Parameter $parameter
     * @return Request
     */
    public function addToParametersStack(Parameter $parameter) :Request
    {
        $this->parameters->add($parameter->key, $parameter);

        return $this;
    }

    /**
     * @return \SplStack
     */
    public function getParametersStack() :StackIndex
    {
        return $this->parameters;
    }

    /**
     * @param $key
     * @param $value
     * @return Request
     */
    public function createParameterInStack($key, $value) :Request
    {
        $parameter = new Parameter($key, $value);
        $this->parameters->add($parameter->key, $parameter);

        return $this;
    }

    /**
     * Return Parameter if found or null if not
     * @param $key
     * @return Parameter
     */
    public function getParameter($key)
    {
        try {
            return $this->parameters->offsetGet($key);
        } catch (\OutOfRangeException $e) {}
        
        return null;
    }

    /**
     * Return Parameter if found or null if not
     * @param $key
     * @return mixed
     */
    public function getParameterValue($key)
    {
        $parameter = $this->getParameter($key);
        
        if (null === $parameter) {
            return null;
        }
        
        return $parameter->value;
    }

    /**
     * @param float $value
     * @return Request
     */
    public function setWeight(float $value) :Request
    {
        $value = number_format($value, 2);
        $value = str_pad($value, 5, '0', STR_PAD_LEFT);

        return $this->createParameterInStack('T530', $value);
    }

    public function getShippingReference()
    {
        $value = $this->getParameterValue('T8975');
        
        if (null === $value) {
            return null;
        }
        
        return substr($value, 2, -6);
    }
}