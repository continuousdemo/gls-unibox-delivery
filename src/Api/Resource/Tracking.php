<?php
/**
 * This file is part of the gls-unixbox-delivery.
 * (c) 2016 Pierre Tomasina
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Plab\GlsUniboxDelivery\Api\Resource;

/**
 * Class Tracking
 * @package Plab\GlsUniboxDelivery\Api\Resource
 */
class Tracking extends Resource
{
    /**
     * @throws \Exception
     */
    public function get()
    {
        Api::httpError(405);
    }

    /**
     * @throws \Exception
     */
    public function post()
    {
        Api::httpError(405);
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