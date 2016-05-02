<?php
/**
 * This file is part of the gls-unixbox-delivery.
 * (c) 2016 Pierre Tomasina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plab\GlsUniboxDelivery\tests\unit\Gls\Request;
use atoum\test;
use Plab\GlsUniboxDelivery\Gls\Request\MandatoryException;

class Basic extends test 
{
    public function testConstructor()
    {
        $this
            ->if($this->newTestedInstance())
        ;

        $this
            ->integer($this->testedInstance->getParameterValue('T8973'))
                ->isIdenticalTo(1)
            ->integer($this->testedInstance->getParameterValue('T8904'))
                ->isIdenticalTo(1)
            ->integer($this->testedInstance->getParameterValue('T8702'))
                ->isIdenticalTo(1)
            ->integer($this->testedInstance->getParameterValue('T8905'))
                ->isIdenticalTo(1)

            ->variable($productCode = $this->testedInstance->getParameterValue('PRODUCT_CODE'))
                ->string($productCode->alpha)
                    ->isIdenticalTo('BP')
                ->string($productCode->numeric)
                    ->isIdenticalTo('02')
                ->string($productCode->uniShip)
                    ->isIdenticalTo('AA')
        ;

        $this
            ->exception(function() {
                $this->testedInstance->checkMandatory();
            })
            ->isInstanceOf(MandatoryException::class)
        ;

        $this->testedInstance
            ->setRecipientAddress1('Main street')
            ->setRecipientZipCode('12345')
            ->setRecipientCity('FiveSquare')
            ->setRecipientCountry('LU')
            ->setRecipientName('John Doe')
            ->setPackageNumber('5469')
            ->setWeight(0.4)
        ;
        
        $this
            ->string($this->testedInstance->getParameterValue('PACKAGE_NUMBER'))
            ->isIdenticalTo('0000005469')
        ;

        $this
            ->boolean($this->testedInstance->checkMandatory())
            ->isTrue()
        ;
    }
}
