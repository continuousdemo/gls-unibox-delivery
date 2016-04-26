<?php
/**
 * This file is part of the gls-unixbox-delivery.
 * (c) Pierre Tomasina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plab\GlsUniboxDelivery\Gls\Response;
/**
 * Class Response
 * @package Plab\GlsUniboxDelivery\Gls\Response
 */
abstract class Response
{
    const DELIMITER = '|';
    const SEPARATOR = ':';
    
    protected $raw;

    public function __construct($raw)
    {
        $this->raw = $raw;

        $this->parse();
    }

    protected function parse()
    {
        $begin =
            '\\' . '\\' . '\\' . '\\' . '\\'
            . 'GLS' .
            '\\' . '\\' . '\\' . '\\' . '\\'
        ;
        $end = '/////GLS/////';

        if (
               $begin !== substr($this->raw, 0, 13)
            || $end   !== substr($this->raw, -13, 13)
        ) {
            throw new Exception('Response do not respect the format expected');
        }

        $parametersExtract = explode(self::DELIMITER, substr($this->raw, 13, -13));

        foreach ($parametersExtract as $parameterExtract) {

            $keyValue = explode(self::SEPARATOR, $parameterExtract, 2);

            if (empty($keyValue) || !property_exists($this, $keyValue[0])) {
                continue;
            }

            $key = $keyValue[0];
            
            $this->$key = empty($keyValue[1]) ? '' : $keyValue[1];
        }
    }

    /**
     * Check if all the expected properties has been set
     * @return mixed
     */
    abstract public function checkProperties();
}