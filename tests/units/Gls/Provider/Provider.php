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
use Plab\GlsUniboxDelivery\Gls\Parameter\Parameter;
use Plab\GlsUniboxDelivery\Gls\Provider\ProviderException;
use Plab\GlsUniboxDelivery\Gls\Request\Basic;

class Provider extends test
{
    /**
     * @return object Basic
     */
    private function createBasicRequest()
    {
        $request = new Basic();
        
        $request
            ->setRecipientAddress1('1 avenue Aristide briand BAT: 01')
            ->setRecipientAddress2('1 avenue aristide briand')
            ->setRecipientZipCode('93190')
            ->setRecipientCity('Livry gargan')
            ->setRecipientCountry('FR')
            ->setRecipientName('yakout ougara')
            ->setRecipientComment('Rien a s')
            ->setRecipientMobile('0695303249')
            ->setWeight(1)
        ;
        
        return $request;
    }

    public static function createConfig() :\Plab\GlsUniboxDelivery\Gls\Provider\Config
    {
        $contactId     = '2504000001';
        $clientCode    = '2500000007';
        $glsWarehouse  = 'FR0031';
        $senderName    = 'IT Company';
        $senderAddress = '42 road street';
        $senderCountry = 'LU';
        $senderZip     = '3636';
        $senderCity    = 'KAYL';

        return new \Plab\GlsUniboxDelivery\Gls\Provider\Config(new \DateTimeImmutable(), $contactId, $clientCode, $glsWarehouse, $senderName, $senderAddress, $senderCountry, $senderZip, $senderCity);
    }

    public function testPrepare()
    {
        $Config = self::createConfig();
        $this->newTestedInstance($Config);

        $basicRequest = $this->createBasicRequest();
        $this
            ->then($this->testedInstance->prepare($basicRequest))
            ->then($message = $this->testedInstance->getMessage())
            ->then($message = substr($message, 14, -14))
            ->then($prepareParameters = explode('|', $message))
        ;

        $parameters = [];
        
        foreach ($prepareParameters as $parameter) {
            list($key, $value) = explode(':', $parameter, 2);
            
            $parameters[$key] = $value;
        }

        foreach ($basicRequest->getParametersStack() as $parameter)
        {
            $this
                ->object($parameter)
                ->isInstanceOf(Parameter::class)
            ;

            $this
                ->boolean(array_key_exists($parameter->key, $parameters))
                ->isTrue()
            ;

            $this
                ->variable($parameter->value)
                ->isEqualTo($parameters[$parameter->key])
            ;
        }
    }

    public function testIncrementPackage()
    {
        $Config = self::createConfig();

        $this
            ->if($this->newTestedInstance($Config))

            ->integer($this->testedInstance->incrementPackage())
                ->isIdenticalTo(1)

            ->integer($this->testedInstance->incrementPackage())
                ->isIdenticalTo(2)

            ->integer($this->testedInstance->incrementPackage())
                ->isIdenticalTo(3)

            ->integer($this->testedInstance->incrementPackage())
                ->isIdenticalTo(4)
        ;
    }

    public function testGetIncrementPackageNumber()
    {
        $Config = self::createConfig();

        $packageNumber = (string)((int)date('Y') + (int)date('m') + (int)date('d') + (int)date('H') + (int)date('i') + (int)date('s')) . '000004';

        $this
            ->if($this->newTestedInstance($Config))
            ->then($this->testedInstance->incrementPackage())
            ->then($this->testedInstance->incrementPackage())
            ->then($this->testedInstance->incrementPackage())

            ->string($this->testedInstance->getIncrementPackageNumber())
                ->hasLength(10)
                ->isIdenticalTo($packageNumber)
        ;
    }

    public function testOrigin()
    {
        $Config = self::createConfig();
        
        $this
            ->if($this->newTestedInstance($Config))
            ->exception(function() {
                $this->testedInstance->origin();
            })
            ->isInstanceOf(ProviderException::class)
            ->hasMessage('You must have request object for obtain build parameter')
        ;

        $basicRequest  = $this->createBasicRequest();
        $packageNumber = (string)((int)date('Y') + (int)date('m') + (int)date('d') + (int)date('H') + (int)date('i') + (int)date('s')) . '000001';

        $this
            ->if($this->newTestedInstance($Config))
            ->then($this->testedInstance->prepare($basicRequest))
            ->then($origin = $this->testedInstance->origin())
            ->object($origin)
                ->isInstanceOf(Parameter::class)
            ->string($origin->value)
                ->hasLength(strlen('0221090000010000FR'))
                ->isIdenticalTo('02' . $packageNumber . '0000' . 'FR')
        ;
    }
}
