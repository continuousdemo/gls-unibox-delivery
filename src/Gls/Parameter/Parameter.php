<?php

/**
 * This file is part of the gls-unixbox-delivery.
 * (c) Pierre Tomasina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plab\GlsUniboxDelivery\Gls\Parameter;

/**
 * Class Parameter
 * @package Plab\GlsUniboxDelivery\Gls\Parameter
 */
class Parameter
{
    const T540 = 'Expedition date';
    const T530 = 'Weight package';
    const T860 = 'Recipient name';
    const T863 = 'Recipient street';
    const T861 = 'Recipient street 2';
    const T862 = 'Recipient street 3';
    const T330 = 'Recipient ZipCode';
    const T864 = 'Recipient City';
    const T100 = 'Recipient Country';
    const T8906 = 'Recipient Comments';
    const T871 = 'Recipient phone';
    const T859 = 'Recipient reference';
    const T854 = 'Extra reference';
    const T8908 = 'Extra reference 2';
    const T1229 = 'Recipient email';
    const T1230 = 'Recipient mobile';
    const T810 = 'Sender gender';
    const T820 = 'Sender address';
    const T821 = 'Sender country';
    const T822 = 'Sender zipCode';
    const T823 = 'Sender city';
    const T8700 = 'GLS Gateway';
    const T8915 = 'GLS ClientCode';
    const T8914 = 'GLS ContactID';
    const T8904 = 'package rank parcel print';
    const T8973 = 'package rank datamatrix barcode';
    const T8905 = 'package sum total parcel print';
    const T8702 = 'package sum total datamatrix barcode';
    const T8975 = 'GLS Origin reference';
    const T082 = 'UNIQUENO';
    const T090 = 'NOSAVE';
    const PRODUCT_CODE = 'Product code of GLS Service';
    const PACKAGE_NUMBER = 'Package number for origin part';

    protected static $parameters;
    protected $key;
    protected $value;

    /**
     * Magic method for turn protected property to readonly access
     * @param $name
     * @return null
     */
    public function __get($key)
    {
        return isset($this->$key) ? $this->$key : null;
    }

    /**
     * return all the parameters
     * @return array
     */
    public static function getParameters()
    {
        if (null === self::$parameters) {

            $reflection = new \ReflectionClass(static::class);
            self::$parameters = $reflection->getConstants();
        }

        return self::$parameters;
    }

    /**
     * @param $key
     * @return bool
     */
    public static function isParameterExists(string $key)
    {
        $parameters = self::getParameters();

        return isset($parameters[$key]);
    }

    public static function checkPhoneNumber(string & $value)
    {
        if (empty($value)) {
            $value = '';
            return true;
        }

        $value = preg_replace('/(\s+|\.)/', '', $value);

        if (20 < strlen($value)) {
            return false;
        }

        return 1 === preg_match('/^((\+|00)[0-9]{2})?[0-9]{8,64}$/', $value);
    }

    public static function checkPackageNumber(string & $value)
    {
        $value = (int)$value;

        return !(empty($value) || 1 > (int)$value || 999 < (int)$value);
    }

    /**
     * Check date format YYYYMMDD
     * @info Expedition date
     * @param string $value
     * @return bool
     */
    public static function checkT540(string & $value)
    {
        if (8 !== strlen($value)) {
            return false;
        }

        $year  = (int) substr($value, 0, 4);
        $month = (int) substr($value, 4, 2);
        $day   = (int) substr($value, 6, 2);

        if (2000 > $year) {
            return false;
        }

        if (12 < $month || 1 > $month) {
            return false;
        }

        if (31 < $day || 1 > $day) {
            return false;
        }

        return true;
    }

    /**
     * @info package weight
     * @param string $value
     * @return bool
     */
    public static function checkT530(string & $value)
    {
        if ('00.00' === $value) {
            return false;
        }

        return 1 === preg_match('/^[0-9]{2}\.[0-9]{2}$/', $value);
    }

    /**
     * @info recipient gender
     * @param string $value
     * @return bool
     */
    public static function checkT860(string & $value)
    {
        return !(empty($value) || 35 < strlen($value));
    }

    /**
     * @info recipient street
     * @param string $value
     * @return bool
     */
    public static function checkT863(string & $value)
    {
        return !(empty($value) || 35 < strlen($value));
    }

    /**
     * @info recipient additional street 1
     * @param string $value
     * @return bool
     */
    public static function checkT861(string & $value)
    {
        return !(35 < strlen($value));
    }

    /**
     * @info recipient additional street 2
     * @param string $value
     * @return bool
     */
    public static function checkT862(string & $value)
    {
        return !(35 < strlen($value));
    }

    /**
     * @info recipient zipCode
     * @param string $value
     * @return bool
     */
    public static function checkT330(string & $value)
    {
        return !(empty($value) || 10 < strlen($value));
    }

    /**
     * @info recipient city
     * @param string $value
     * @return bool
     */
    public static function checkT864(string & $value)
    {
        return !(empty($value) || 35 < strlen($value));
    }

    /**
     * @info recipient country
     * @param string $value
     * @return bool
     */
    public static function checkT100(string & $value)
    {
        //TODO must be iso 3366 e.g FR

        if (2 !== strlen($value)) {
            return false;
        }

        return true;
    }

    /**
     * @info recipient comment
     * @param string $value
     * @return bool
     */
    public static function checkT8906(string & $value)
    {
        return !(35 < strlen($value));
    }

    /**
     * @info recipient phone
     * @param string $value
     * @return bool
     */
    public static function checkT871(string & $value)
    {
        return self::checkPhoneNumber($value);
    }

    /**
     * @info recipient reference
     * @param string $value
     * @return bool
     */
    public static function checkT859(string & $value)
    {
        return !(20 < strlen($value));
    }

    /**
     * @info reference sup 1
     * @param string $value
     * @return bool
     */
    public static function checkT854(string & $value)
    {
        return !(20 < strlen($value));
    }

    /**
     * @info reference sup 2
     * @param string $value
     * @return bool
     */
    public static function checkT8908(string & $value)
    {
        return !(20 < strlen($value));
    }

    /**
     * @info recipient email
     * @param string $value
     * @return bool
     */
    public static function checkT1229(string & $value)
    {
        $value = strtolower($value);
        $value = filter_var($value, FILTER_VALIDATE_EMAIL);

        return !(false === $value);
    }

    /**
     * @info recipient mobile
     * @param string $value
     * @return bool
     */
    public static function checkT1230(string & $value)
    {
        return self::checkPhoneNumber($value);
    }

    /**
     * @info sender gender
     * @param string $value
     * @return bool
     */
    public static function checkT810(string & $value)
    {
        return !(empty($value) || 35 < strlen($value));
    }

    /**
     * @info sender address
     * @param string $value
     * @return bool
     */
    public static function checkT820(string & $value)
    {
        return !(empty($value) || 35 < strlen($value));
    }

    /**
     * @info sender country
     * @param string $value
     * @return bool
     */
    public static function checkT821(string & $value)
    {
        //TODO must be iso 3366 e.g FR

        if (2 !== strlen($value)) {
            return false;
        }

        return true;
    }

    /**
     * @info sender zipCode
     * @param string $value
     * @return bool
     */
    public static function checkT822(string & $value)
    {
        return !(empty($value) || 10 < strlen($value));
    }

    /**
     * @info sender city
     * @param string $value
     * @return bool
     */
    public static function checkT823(string & $value)
    {
        return !(empty($value) || 35 < strlen($value));
    }

    /**
     * @info sender gls gateway
     * @param string $value
     * @return bool
     */
    public static function checkT8700(string & $value)
    {
        $value = preg_replace('/\s+/', '', $value);

        return !(6 !== strlen($value));
    }

    /**
     * @info gls code client
     * @param string $value
     * @return bool
     */
    public static function checkT8915(string & $value)
    {
        return !(10 !== strlen($value));
    }

    /**
     * @info gls contact id
     * @param string $value
     * @return bool
     */
    public static function checkT8914(string & $value)
    {
        return !(10 !== strlen($value));
    }

    /**
     * @info gls package number print
     * @param string $value
     * @return bool
     */
    public static function checkT8904(string & $value)
    {
        return self::checkPackageNumber($value);
    }

    /**
     * @info gls package number datamatrix
     * @param string $value
     * @return bool
     */
    public static function checkT8973(string & $value)
    {
        return self::checkPackageNumber($value);
    }

    /**
     * @info gls package total print
     * @param string $value
     * @return bool
     */
    public static function checkT8905(string & $value)
    {
        return self::checkPackageNumber($value);
    }

    /**
     * @info gls package total datamatrix
     * @param string $value
     * @return bool
     */
    public static function checkT8702(string & $value)
    {
        return self::checkPackageNumber($value);
    }

    /**
     * @info gls origin
     * @param string $value
     * @return bool
     */
    public static function checkT8975(string & $value)
    {
        return !(empty($value));
    }

    /**
     * @info
     * @param string $value
     * @return bool
     */
    public static function checkT082(string & $value)
    {
        return ($value === self::T082);
    }

    /**
     * @info
     * @param string $value
     * @return bool
     */
    public static function checkT090(string & $value)
    {
        return ($value === self::T090);
    }

    /**
     * @param ProductCode $value
     * @return bool
     */
    public static function checkPRODUCT_CODE(ProductCode $value)
    {
        return true;
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function checkPACKAGE_NUMBER(string & $value)
    {
        if (10 < strlen($value)) {
            return false;
        }

        $value = str_pad($value, 10, '0', STR_PAD_LEFT);

        return 1 === preg_match('/^[0-9]{10}$/', $value);
    }

    /**
     * @param $key
     * @param $value
     */
    public function __construct(string $key = null, $value = null)
    {
        if (null !== $key) {
            $this->setKey($key);
        }

        if (null !== $value) {
            $this->setValue($value);
        }
    }

    /**
     * @param $key
     * @throws ParameterException
     */
    public function setKey($key)
    {
        $this->key = $key;

        if (false === self::isParameterExists($this->key)) {
            throw new ParameterException("Parameter {$this->key} non exist");
        }
    }

    /**
     * @param $value
     * @throws ParameterException
     */
    public function setValue($value)
    {
        if (is_string($value)) {

            $ranges = [
                '\x{0021}-\x{0022}',
                '\x{0024}-\x{0029}',
                '\x{002B}-\x{002D}',
                '\x{002F}-\x{002F}',
                '\x{003B}-\x{003F}',
                '\x{005B}-\x{0060}',
                '\x{007B}-\x{007F}',
                '\x{0080}-\x{008D}',
                '\x{0091}-\x{009D}',
                '\x{00A4}-\x{00BF}',
            ];

            $regexp = '/[' . implode(']|[', $ranges) . ']/u';

            $value = preg_replace($regexp, '', $value);
            $value = preg_replace('/\s+/', ' ', $value);
        }

        $this->value = $value;

        if (false === $this->isValid()) {
            throw new ParameterException("Parameter {$this->key} invalid");
        }
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if (null === $this->key || false === self::isParameterExists($this->key)) {
            throw new ParameterException("Parameter {$this->key} non exist");
        }

        $method = "check{$this->key}";

        if (false === method_exists($this, $method)) {
            return true;
        }

        return self::$method($this->value);
    }
}