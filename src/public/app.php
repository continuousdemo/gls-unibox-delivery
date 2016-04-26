<?php

require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

define('__DEV_MODE__', true);

$contactId     = '2500000001';
$clientCode    = '2500000007';
$glsWarehouse  = 'FR0057';
$senderName    = 'MY COMPANY';
$senderAddress = '42 main street';
$senderCountry = 'LU';
$senderZip     = '3636';
$senderCity    = 'KAYL';

$config = new \Plab\GlsUniboxDelivery\Gls\Provider\Config(new \DateTimeImmutable(), $contactId, $clientCode,
    $glsWarehouse, $senderName, $senderAddress, $senderCountry, $senderZip, $senderCity
);

$provider = new \Plab\GlsUniboxDelivery\Gls\Provider\Provider($config);

/***************************** */

$API = new \Plab\GlsUniboxDelivery\Api\Api($provider);
$API->run();
