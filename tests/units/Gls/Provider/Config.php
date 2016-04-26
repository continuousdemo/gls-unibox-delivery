<?php
/**
 * This file is part of the gls-unixbox-delivery.
 * (c) 2016 Pierre Tomasina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plab\GlsUniboxDelivery\tests\unit\Gls\Provider;
use atoum\test;

class Config extends test
{
    public function testConstruct()
    {
        $this
            ->exception(function() {
                try {
                    new \Plab\GlsUniboxDelivery\Gls\Provider\Config();
                } catch (\TypeError $e) {
                    throw new \Exception('Error');
                }
            })
            ->isInstanceOf(\Exception::class)
        ;

        $time = '2016-04-21T11:28:27+02:00';

        $shippingDate  = new \DateTimeImmutable($time);
        $contactId     = '2504000001';
        $clientCode    = '2500000007';
        $glsWarehouse  = 'FR0031';
        $senderName    = 'IT Company';
        $senderAddress = '42 road street';
        $senderCountry = 'LU';
        $senderZip     = '3636';
        $senderCity    = 'KAYL';

        $this
            ->if($this->newTestedInstance($shippingDate, $contactId, $clientCode, $glsWarehouse, $senderName, $senderAddress, $senderCountry, $senderZip, $senderCity))
            ->object($this->testedInstance)

            ->string($this->testedInstance->shippingDate->value)
                ->isIdenticalTo('20160421')

            ->string($this->testedInstance->contactId->value)
                ->isIdenticalTo($contactId)

            ->string($this->testedInstance->clientCode->value)
                ->isIdenticalTo($clientCode)

            ->string($this->testedInstance->glsWarehouse->value)
                ->isIdenticalTo($glsWarehouse)

            ->string($this->testedInstance->senderName->value)
                ->isIdenticalTo($senderName)

            ->string($this->testedInstance->senderAddress->value)
                ->isIdenticalTo($senderAddress)

            ->string($this->testedInstance->senderCountry->value)
                ->isIdenticalTo($senderCountry)

            ->string($this->testedInstance->senderZip->value)
                ->isIdenticalTo($senderZip)

            ->string($this->testedInstance->senderCity->value)
                ->isIdenticalTo($senderCity)
        ;
    }
}
