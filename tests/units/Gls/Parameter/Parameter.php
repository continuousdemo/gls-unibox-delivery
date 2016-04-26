<?php
/**
 * This file is part of the gls-unixbox-delivery.
 * (c) Pierre Tomasina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plab\GlsUniboxDelivery\tests\units\Gls\Parameter;
use atoum\test;
use Plab\GlsUniboxDelivery\Gls\Parameter\ProductCode;

class Parameter extends test
{
    public function testGetParameters()
    {
        $parameters = \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::getParameters();

        $this
            ->array(array_keys($parameters))->isIdenticalTo([
                'T540',
                'T530',
                'T860',
                'T863',
                'T861',
                'T862',
                'T330',
                'T864',
                'T100',
                'T8906',
                'T871',
                'T859',
                'T854',
                'T8908',
                'T1229',
                'T1230',
                'T810',
                'T820',
                'T821',
                'T822',
                'T823',
                'T8700',
                'T8915',
                'T8914',
                'T8904',
                'T8973',
                'T8905',
                'T8702',
                'T8975',
                'T082',
                'T090',
                'PRODUCT_CODE',
            ])
        ;
    }

    public function isParameterExistsDataProvider()
    {
        return [
            ['T822', true],
            ['t822', false],
            ['F584', false],
            ['T001', false],
            ['Foo bar', false],
            ['Baz', false],
            ['T1230', true],
            ['T082', true],
            ['T8904', true],
            ['T863', true],
            ['T090', true],
        ];
    }

    /**
     * @dataProvider isParameterExistsDataProvider
     * @param $value
     * @param $result
     */
    public function testisParameterExists(string $key, bool $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::isParameterExists($key))
            ->isIdenticalTo($result)
        ;
    }

    /**
     * @engine inline
     */
    public function testIsValid()
    {
        $this
            ->if($this->newTestedInstance())
            ->exception(function() {
                $this->testedInstance->isValid();
            })
            ->isInstanceOf( \Plab\GlsUniboxDelivery\Gls\Parameter\ParameterException::class)
        ;

        $this
            ->exception(function() {
                $this->newTestedInstance(null, 'foo');
            })
            ->isInstanceOf( \Plab\GlsUniboxDelivery\Gls\Parameter\ParameterException::class)
        ;

        $this
            ->exception(function() {
                $this->newTestedInstance('foo');
            })
            ->isInstanceOf( \Plab\GlsUniboxDelivery\Gls\Parameter\ParameterException::class)
        ;

        $this
            ->if($this->newTestedInstance('T540'))
            ->string($this->testedInstance->key)
            ->isIdenticalTo('T540')
        ;

        $this
            ->if($this->newTestedInstance('T540', '20160418'))
            ->boolean($this->testedInstance->isValid())
            ->isTrue()
        ;
    }

    /**
     * This data provider return string that have true value of less than or equal to 35 chars
     * @return array
     */
    public function string35LengthDataProvider()
    {
        return [
            ['Street of the unicorn should not be more than 35 characters', false],
            ['Street of are equal of 36 characters', false],
            ['Street of are equal of 35 character', true],
            ['Street of are equalOf 34 character', true],
            ['This street is good', true],
            ['This street with 42 number', true],
        ];
    }

    /**
     * This data provider return string that have true value of less than or equal to 10 chars
     * @return array
     */
    public function string20LengthDataProvider()
    {
        return [
            ['should not be more than 20 characters', false],
            ['21 chars foo bar baz ', false],
            ['20 chars foo bar baz', true],
            ['is good', true],
        ];
    }

    /**
     * This data provider return string that have true value of less than or equal to 10 chars
     * @return array
     */
    public function string10LengthDataProvider()
    {
        return [
            ['should not be more than 10 characters', false],
            ['11 char xx ', false],
            ['10 char xx', true],
            ['is good', true],
        ];
    }

    public function T540DataProvider()
    {
        $data = [];

        $i = 2000;

        while ($i--) {

            $year  = str_pad((string) mt_rand(-150, 1999), 2, '0', STR_PAD_LEFT);
            $month = str_pad((string) mt_rand(1, 12), 2, '0', STR_PAD_LEFT);
            $day   = str_pad((string) mt_rand(1, 31), 2, '0', STR_PAD_LEFT);

            $data[] = [$year . $month . $day, false];

            $year  = str_pad((string) mt_rand(2000, 2020), 2, '0', STR_PAD_LEFT);
            $month = str_pad((string) mt_rand(13, 200), 2, '0', STR_PAD_LEFT);
            $day   = str_pad((string) mt_rand(1, 31), 2, '0', STR_PAD_LEFT);

            $data[] = [$year . $month . $day, false];

            $year  = str_pad((string) mt_rand(2000, 2020), 2, '0', STR_PAD_LEFT);
            $month = str_pad((string) mt_rand(1, 12), 2, '0', STR_PAD_LEFT);
            $day   = str_pad((string) mt_rand(-10, 0), 2, '0', STR_PAD_LEFT);

            $data[] = [$year . $month . $day, false];

            $year  = str_pad((string) mt_rand(2000, 2020), 2, '0', STR_PAD_LEFT);
            $month = str_pad((string) mt_rand(1, 12), 2, '0', STR_PAD_LEFT);
            $day   = str_pad((string) mt_rand(1, 31), 2, '0', STR_PAD_LEFT);

            $data[] = [$year . $month . $day, true];
        }

        return $data;
    }

    /**
     * @dataProvider T540DataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT540($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT540($value))
            ->isIdenticalTo($result)
        ;
    }

    public function T530DataProvider()
    {
        return [
            ['00.01', true],
            ['00.10', true],
            ['01.00', true],
            ['01.50', true],
            ['10.00', true],
            ['99.99', true],
            ['00.00', false],
            ['09.00', true],
            ['01.01', true],
            ['00.99', true],
            ['89.111', false],
            ['89', false],
            ['89.1', false],
            ['0.111', false],
            ['10.11', true],
            ['1.111', false],
        ];
    }

    /**
     * @dataProvider T530DataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT530($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT530($value))
            ->isIdenticalTo($result)
        ;
    }

    /**
     * @dataProvider string35LengthDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT860($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT860($value))
            ->isIdenticalTo($result)
        ;
    }

    /**
     * @dataProvider string35LengthDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT863($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT863($value))
            ->isIdenticalTo($result)
        ;
    }

    /**
     * @dataProvider string35LengthDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT861($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT861($value))
            ->isIdenticalTo($result)
        ;
    }

    /**
     * @dataProvider string35LengthDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT862($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT862($value))
            ->isIdenticalTo($result)
        ;
    }

    /**
     * @dataProvider string10LengthDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT330($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT330($value))
            ->isIdenticalTo($result)
        ;
    }

    /**
     * @dataProvider string35LengthDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT864($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT864($value))
            ->isIdenticalTo($result)
        ;
    }

    public function countriesDataProvider()
    {
        return [
            ['FR', true],
            ['US', true],
            ['UK', true],
            ['PL', true],
            ['IT', true],
            ['ES', true],
            ['XXX', false],
        ];
    }

    /**
     * @dataProvider countriesDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT100($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT100($value))
            ->isIdenticalTo($result)
        ;
    }

    /**
     * @dataProvider string35LengthDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT8906($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT8906($value))
            ->isIdenticalTo($result)
        ;
    }

    public function phonesDataProvider()
    {
        return [
            ['0033648099915', true],
            ['+33648099915', true],
            ['+35226561167', true],
            ['0035226561167', true],
            ['+3522656116740', true],
            ['+3522656116740A', false],
            ['A3522656116740', false],
            ['0035226A561167', false],
            ['', true],
        ];
    }

    /**
     * @dataProvider phonesDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT871($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT871($value))
            ->isIdenticalTo($result)
        ;
    }

    /**
     * @dataProvider string20LengthDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT859($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT859($value))
            ->isIdenticalTo($result)
        ;
    }

    /**
     * @dataProvider string20LengthDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT854($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT854($value))
            ->isIdenticalTo($result)
        ;
    }

    /**
     * @dataProvider string20LengthDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT8908($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT8908($value))
            ->isIdenticalTo($result)
        ;
    }

    public function emailDataProvider()
    {
        return [
            ['email@domain.tld', true],
            ['example@example.org', true],
            ['dummy', false],
            ['example@localhost.localdomain', true],
            ['x+yyy@domain.tld', true],
            ['foo@2131.org', true], //TODO I've couple doubt the rfc email authorise number
        ];
    }

    /**
     * @dataProvider emailDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT1229($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT1229($value))
            ->isIdenticalTo($result)
        ;
    }

    /**
     * @dataProvider phonesDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT1230($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT1230($value))
            ->isIdenticalTo($result)
        ;
    }

    /**
     * @dataProvider string35LengthDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT810($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT810($value))
            ->isIdenticalTo($result)
        ;
    }

    /**
     * @dataProvider string35LengthDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT820($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT820($value))
            ->isIdenticalTo($result)
        ;
    }

    /**
     * @dataProvider countriesDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT821($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT821($value))
            ->isIdenticalTo($result)
        ;
    }

    /**
     * @dataProvider string10LengthDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT822($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT822($value))
            ->isIdenticalTo($result)
        ;
    }

    /**
     * @dataProvider string35LengthDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT823($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT823($value))
            ->isIdenticalTo($result)
        ;
    }

    public function T8700DataProvider()
    {
        return [
            ['FR0000', true],
            ['FR0001', true],
            ['FR00001', false],
            ['FR0000 ', true],
            ['   FR0000 ', true],
            ['R0000', false],
            ['FR000 ', false],
            ['', false],
            ['X', false],
        ];
    }

    /**
     * @dataProvider T8700DataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT8700($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT8700($value))
            ->isIdenticalTo($result)
        ;
    }

    public function T8915DataProvider()
    {
        return [
            ['2500000000', true],
            ['2500000001', true],
            ['250000000 ', true],
            ['500000000', false],
            [' 500000000', true],
            ['500000000', false],
            ['25000000001', false],
        ];
    }

    /**
     * @dataProvider T8915DataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT8915($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT8915($value))
            ->isIdenticalTo($result)
        ;
    }

    public function T8914DataProvider()
    {
        return [
            ['2500000000', true],
            ['2500000001', true],
            ['250000000 ', true],
            ['500000000', false],
            [' 500000000', true],
            ['500000000', false],
            ['25000000001', false],
        ];
    }

    /**
     * @dataProvider T8914DataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT8914($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT8914($value))
            ->isIdenticalTo($result)
        ;
    }

    public function packageNumberDataProvider()
    {
        $data = [
            [0, false],
            [-1, false],
            [1000, false],
            [1, true],
            [999, true],
            ['1', true],
            ['10', true],
            ['8', true],
            ['02', true],
            ['61', true],
            ['999', true],
        ];

        $i = 200;

        while ($i--) {
            $data[] = [mt_rand(1, 999), true];
            $data[] = [mt_rand(-10, 0), false];
            $data[] = [mt_rand(1000, 99999999), false];
        }

        return $data;
    }

    /**
     * @dataProvider packageNumberDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT8904($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT8904($value))
            ->isIdenticalTo($result)
        ;
    }

    /**
     * @dataProvider packageNumberDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT8973($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT8973($value))
            ->isIdenticalTo($result)
        ;
    }

    /**
     * @dataProvider packageNumberDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT8905($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT8905($value))
            ->isIdenticalTo($result)
        ;
    }

    /**
     * @dataProvider packageNumberDataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT8702($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT8702($value))
            ->isIdenticalTo($result)
        ;
    }

    public function T8975DataProvider()
    {
        return [
            ['', false],
            ['foo', true],
        ];
    }

    /**
     * @dataProvider T8975DataProvider
     * @param $value
     * @param $result
     */
    public function testCheckT8975($value, $result)
    {
        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT8975($value))
            ->isIdenticalTo($result)
        ;
    }

    public function testCheckT082()
    {
        $value = \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::T082;

        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT082($value))
            ->isTrue()
        ;

        $value = 'Foo';

        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT082($value))
            ->isFalse()
        ;
    }

    public function testCheckT090()
    {
        $value = \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::T090;

        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT090($value))
            ->isTrue()
        ;

        $value = 'Foo';

        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkT090($value))
            ->isFalse()
        ;
    }

    public function testCheckPRODUCT_CODE()
    {
        $value = new ProductCode('BP');

        $this
            ->boolean( \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkPRODUCT_CODE($value))
            ->isTrue()
        ;

        $this
            ->exception(function() {
                try {
                    $value = 'Foo';
                    \Plab\GlsUniboxDelivery\Gls\Parameter\Parameter::checkPRODUCT_CODE($value);
                } catch (\TypeError $e) {
                    throw new \Exception();
                }
            })
        ;
    }

    public function testSetValue()
    {
        $this
            ->if($this->newTestedInstance('T854', '09:39'))
            ->string($this->testedInstance->value)
                ->isIdenticalTo('09:39')
        ;
    }
}