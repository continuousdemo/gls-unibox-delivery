<?php
/**
 * This file is part of the gls-unixbox-delivery.
 * (c) 2016 Pierre Tomasina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Plab\GlsUniboxDelivery\Gls\Parameter;

use mageekguy\atoum\tests\units\writers\std;
use Plab\GlsUniboxDelivery\Gls\ValueObject;

/**
 * Class ProductCode
 * @package Plab\GlsUniboxDelivery\Gls\Parameter
 */
final class ProductCode extends ValueObject
{
    protected $alpha;
    protected $numeric;
    protected $uniShip;

    const MAP = [
        'BP' => [
            'num' => '02',
            'uni' => 'AA',
        ],
        'EBP' => [
            'num' => '01',
            'uni' => 'CC',
        ],
        'GBP' => [
            'num' => '01',
            'uni' => 'FF',
        ],
        'EP' => [
            'num' => '16',
            'uni' => null,
        ],
        'SHD' => [
            'num' => '17',
            'uni' => null,
        ],
        'FDF' => [
            'num' => '18',
            'uni' => null,
        ],
    ];

    /**
     * ProductCode constructor.
     * @param string $alpha
     */
    public function __construct(string $alpha)
    {
        $alpha = strtoupper($alpha);
        
        if (!array_key_exists($alpha, self::MAP)) {
            throw new ParameterException('invalid value for ProductCode variable');
        }
        
        $this->alpha   = $alpha;
        $this->numeric = self::MAP[$alpha]['num'];
        $this->uniShip = self::MAP[$alpha]['uni'];
    }

    /**
     * @return string
     */
    public function __toString() :string
    {
        return $this->alpha;
    }
}