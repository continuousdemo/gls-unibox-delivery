<?php
/**
 * This file is part of the gls-unixbox-delivery.
 * (c) 2016 Pierre Tomasina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Plab\GlsUniboxDelivery\Api\Resource;
use Plab\GlsUniboxDelivery\Api\Api;
use Plab\GlsUniboxDelivery\Generator\Pdf\Html2Pdf\Html2Pdf;
use Plab\GlsUniboxDelivery\Gls\Parameter\ParameterException;

/**
 * Class Parcel
 * @package Plab\GlsUniboxDelivery\Api\Resource
 */
class Parcel extends Resource
{
    /**
     * @throws \Exception
     */
    public function get() 
    {
        Api::httpError(405);
    }

    public function post()
    {
        $recipients = $this->bodyAsJson();
        $parcelsError = [];

        if (null === $recipients) {
            return;
        }

        $pdf = new Html2Pdf();

        foreach ($recipients as $recipient)
        {
            $address2 = '';
            $comment  = '';

            $request = new \Plab\GlsUniboxDelivery\Gls\Request\Basic();

            if (!empty($recipient->address2)) {
                $address2 = $recipient->address2;
            }

            if (!empty($recipient->comment)) {
                $comment = $recipient->comment;
            }

            if (35 < strlen($address2)) {
                $address2 = substr($address2, 0, 35);
                $comment = substr($address2, 35) . $comment;

                if (35 < strlen($comment)) {
                    $comment = substr($comment, 0, 35);
                }
            }
            
            try {
                $request
                    ->setRecipientAddress1($recipient->address1)
                    ->setRecipientZipCode($recipient->zipCode)
                    ->setRecipientCity($recipient->city)
                    ->setRecipientCountry($recipient->country)
                    ->setRecipientName($recipient->name)
                    ->setRecipientMobile($recipient->mobile)
                    ->setRecipientReference($recipient->reference)
                    ->setWeight($recipient->weight)
                ;
            } catch (ParameterException $e) {
                
                $parcelsError[] = (object)[
                    'reference' => $recipient->reference,
                    'error'     => $e->getMessage(),
                ];
                
                continue;
            }
            
            if (!empty($address2)) {
                $request->setRecipientAddress2($address2);
            }
            if (!empty($comment)) {
                $request->setRecipientComment($comment);
            }

            $this->provider->prepare($request);

            try {
                $response = $this->provider->run();
                $Parcel = new \Plab\GlsUniboxDelivery\Generator\Pdf\Html2Pdf\Parcel($response, $request);
            } catch (\Plab\GlsUniboxDelivery\Gls\Provider\ProviderWsTimeoutException $e) {

                $Parcel = new \Plab\GlsUniboxDelivery\Generator\Pdf\Html2Pdf\Parcel(null, $request);
                $Parcel->setTemplate(\Plab\GlsUniboxDelivery\Generator\Pdf\Html2Pdf\Parcel::TEMPLATE_UNISHIP);
            }

            $pdf->addParcel($Parcel);
        }

        $result = $pdf->render();
        
        $result->parcels += $parcelsError;
        
        $this->result('json', $result);
    }

    /**
     * @throws \Exception
     */
    public function put()
    {
        Api::httpError(405);
    }

    /**
     * @throws \Exception
     */
    public function update()
    {
        Api::httpError(405);
    }

    /**
     * @throws \Exception
     */
    public function delete()
    {
        Api::httpError(405);
    }

    /**
     * @throws \Exception
     */
    public function head()
    {
        Api::httpError(405);
    }
}