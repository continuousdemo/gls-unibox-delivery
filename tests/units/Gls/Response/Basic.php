<?php
/**
 * This file is part of the gls-unixbox-delivery.
 * (c) 2016 Pierre Tomasina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plab\GlsUniboxDelivery\tests\unit\Gls\Response;
use atoum\test;

class Basic extends test 
{
    public function testConstructor()
    {
        $raw = '\\\\\\\\\\GLS\\\\\\\\\\PRODUCT_CODE:BP|T863:1 avenue Aristide briand BAT 01|T861:1 avenue aristide briand|T330:93190|T864:Livry gargan|T860:yakout ougara|T8906:Rien a s|T1230:0695303249|T859:0100|T082:UNIQUENO|T090:NOSAVE|T8973:1|T8904:1|T8702:1|T8905:1|T8914:2504000001|T8915:2500000007|T8700:FR0031|T810:IT Company|T820:42 road street|T821:LU|T822:3636|T823:KAYL|T8975:20830000010000|T080:V83_10_0003|T520:20042016|T510:de|T500:FR0031|T103:FR0031|T560:FR01|T8797:IBOXCUS|T540:22.04.2016|T541:01:01|T100:FR|CTRA2:FR|T210:|ARTNO:Standard|T530:|T206:BP|ALTZIP:93190|FLOCCODE:FR0092|TOURNO:4785|T320:4785|TOURTYPE:21102|SORT1:7|T310:7|T331:93190|T890:9250|ROUTENO:78005|ROUTE1:FRY|T110:FRY|FLOCNO:139|T101:0092|T105:FR|T300:25099279|T805:|NDI:|T400:001458473155|NSN:12192501001440160584731500000930|T610:12192501001440160584731500000930|T8603:12192501001440160584731500000930|T8970:A|T8971:A|T8980:AA|T8974:|T8950:Tour|T8951:ZipCode|T8952:Your GLS Track ID|T8953:Product|T8954:Service Code|T8955:Delivery address|T8956:Contact|T8958:Contact|T8957:Customer ID|T8959:Phone|T8960:Note|T8961:Parcel|T8962:Weight|T8965:Contact ID|T8976:20830000010000|T8913:002EU0IB|T8972:002EU0IB|T8902:AFR0031FR009225000000072504000001002EU0IBAA          7FRY478593190  0000000100120830000010000        20830000010000        |T8903:A\7Cyakout ougara\7C1 avenue                                                       |T102:FR0092|PRINTINFO:|PRINT1:|RESULT:E000:001458473155|PRINT0:xxGLSintermecpf4i.int01|/////GLS/////';
        
        $this
            ->if($this->newTestedInstance($raw))

            ->string($this->testedInstance->T330)
                ->isIdenticalTo('93190')

            ->string($this->testedInstance->T863)
                ->isIdenticalTo('1 avenue Aristide briand BAT 01')

            ->string($this->testedInstance->T864)
                ->isIdenticalTo('Livry gargan')

            ->string($this->testedInstance->T860)
                ->isIdenticalTo('yakout ougara')

            ->string($this->testedInstance->T8915)
                ->isIdenticalTo('2500000007')

            ->string($this->testedInstance->T8700)
                ->isIdenticalTo('FR0031')

            ->string($this->testedInstance->T810)
                ->isIdenticalTo('IT Company')
        ;
    }
}
