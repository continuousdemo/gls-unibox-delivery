<?php
/**
 * This file is part of the gls-unixbox-delivery.
 * (c) 2016 Pierre Tomasina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plab\GlsUniboxDelivery\Gls\Provider;

use Plab\GlsUniboxDelivery\Gls\Parameter\Parameter;
use Plab\GlsUniboxDelivery\Gls\ValueObject;

final class Config extends ValueObject
{
    protected $shippingDate;
    protected $contactId;
    protected $clientCode;
    protected $glsWarehouse;
    protected $senderName;
    protected $senderAddress;
    protected $senderCountry;
    protected $senderZip;
    protected $senderCity;

    const ParametersMap = [
        'shippingDate'  => 'T540',
        'contactId'     => 'T8914',
        'clientCode'    => 'T8915',
        'glsWarehouse'  => 'T8700',
        'senderName'    => 'T810',
        'senderAddress' => 'T820',
        'senderCountry' => 'T821',
        'senderZip'     => 'T822',
        'senderCity'    => 'T823',
    ];

    const mandatoryParameters = [
        'shippingDate',
        'contactId',
        'clientCode',
        'glsWarehouse',
        'senderName',
        'senderAddress',
        'senderCountry',
        'senderZip',
        'senderCity',
    ];

    public function __construct(
        \DateTimeImmutable $shippingDate
        , string $contactId
        , string $clientCode
        , string $glsWarehouse
        , string $senderName
        , string $senderAddress
        , string $senderCountry
        , string $senderZip
        , string $senderCity
    ) {
        $reflectionMethod     = new \ReflectionMethod(self::class, '__construct');
        $reflectionParameters = $reflectionMethod->getParameters();

        foreach ($reflectionParameters as $reflectionParameter) {

            $name = $reflectionParameter->name;

            if (!array_key_exists($name, self::ParametersMap)) {

                throw new ProviderException("Parameter {$name} non exist");
            }

            $this->set($name, $$name);
        }
    }

    protected function set($name, $value)
    {
        if ($value === null && array_key_exists($name, self::mandatoryParameters)) {
            throw new ProviderException("value cannot be null for mandatory parameter");
        }

        $ParametersMap = self::ParametersMap;
        $key           = $ParametersMap[$name];

        if ($name === 'shippingDate') {
            $value = $value->format('Ymd');
        }

        $Parameter = new Parameter($key, $value);

        if (!$Parameter->isValid()) {
            throw new ProviderException("The parameter $name are invalid");
        }

        $this->$name = $Parameter;
    }
}