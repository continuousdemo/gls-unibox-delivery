<?php
/**
 * This file is part of the gls-unixbox-delivery.
 * (c) 2016 Pierre Tomasina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plab\GlsUniboxDelivery\tests\units\Gls\Parameter;
use atoum\test;
use Plab\GlsUniboxDelivery\Gls\Parameter\ParameterException;

class ProductCode extends test 
{
    public function testConstruct()
    {
        $this
            ->exception(function() {
                try {
                    new \Plab\GlsUniboxDelivery\Gls\Parameter\ProductCode();
                } catch (\TypeError $e) {
                    throw new \Exception();
                }
            })
        ;

        $this
            ->then($this->newTestedInstance('BP'))
            ->string($this->testedInstance->alpha)
                ->isIdenticalTo('BP')
            ->string($this->testedInstance->numeric)
                ->isIdenticalTo('02')
            ->string($this->testedInstance->uniShip)
                ->isIdenticalTo('AA')
        ;

        $this
            ->exception(function() {
                $this->newTestedInstance('FooBar');
            })
            ->isInstanceOf(ParameterException::class)
        ;
    }

    public function testToString()
    {
        $this
            ->then($this->newTestedInstance('BP'))
            ->string((string)$this->testedInstance)
            ->isIdenticalTo('BP')
        ;
    }
}
