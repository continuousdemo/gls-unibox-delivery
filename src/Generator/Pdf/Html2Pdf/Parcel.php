<?php
/**
 * This file is part of the gls-unixbox-delivery.
 * (c) Pierre Tomasina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plab\GlsUniboxDelivery\Generator\Pdf\Html2Pdf;

use Plab\GlsUniboxDelivery\Gls\Request\Request;
use Plab\GlsUniboxDelivery\Gls\Response\Response;

class Parcel implements ParcelInterface
{
    const TEMPLATE_BASIC = 'basic';
    const TEMPLATE_UNISHIP = 'uniship';
    
    protected $response;
    protected $request;
    protected $template;

    public function __construct(Response $response = null, Request $request)
    {
        $this->response = $response;
        $this->request  = $request;
        $this->template = self::TEMPLATE_BASIC;
    }

    public function setTemplate(string $template)
    {
        $this->template = $template;
    }
    
    public function extract()
    {
        //TODO SHOULD be a class (maybe valueObject) with toJson method
        
        return (object)[
            'reference' => $this->response->T859,
            'tracking'  => $this->response->T8913,
        ];
    }

    public function firstMatrix()
    {
        return str_pad($this->response->T8902, 123, ' ', STR_PAD_RIGHT);
    }

    public function secondMatrix()
    {
        return str_pad($this->response->T8903, 106, ' ', STR_PAD_RIGHT);
    }

    public function uniMatrix()
    {
        $productCode = $this->request->getParameter('PRODUCT_CODE');

        $rescuesParams = [
            1  => 'A',
            2  => $this->request->getParameterValue('T8915'),
            3  => $this->request->getParameterValue('T8914'),
            4  => $productCode->uniShip,
            5  => '250', //ISO 3166 of country
            6  => $this->request->getParameterValue('T330'),
            7  => $this->request->getParameterValue('T8905'),
            8  => $this->request->getParameterValue('T8973'),
            9  => $this->request->getShippingReference(),
            10 => $this->request->getParameterValue('T860'),
            11 => $this->request->getParameterValue('T861'),
            12 => $this->request->getParameterValue('T862'),
            13 => $this->request->getParameterValue('T863'),
            14 => '',
            15 => $this->request->getParameterValue('T864'),
            16 => $this->request->getParameterValue('T871'),
            17 => $this->request->getParameterValue('T854'),
            18 => $this->request->getParameterValue('T8975'),
            19 => $this->request->getParameterValue('T530'),
            20 => '',
        ];

        $rescueMatrix = implode('|', $rescuesParams);
        $lenRequired = 304;

        return str_pad($rescueMatrix, $lenRequired - 1, ' ', STR_PAD_RIGHT) . '|';
    }
    
    public function parseTemplate()
    {
        $resPath  = __DIR__ . DIRECTORY_SEPARATOR . 'res';
        
        ob_start();
        include __DIR__ . '/templates/' . $this->template . '.phtml';
        $content = ob_get_clean();
        
        return $content;
    }
}