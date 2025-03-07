<?php

/**
 * ReadBytes trait
 *
 * PHP version 5
 *
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2015 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 *
 * Modified by woocommerce on 02-December-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Automattic\WooCommerce\Bookings\Vendor\phpseclib3\System\SSH\Common\Traits;

/**
 * ReadBytes trait
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
trait ReadBytes
{
    /**
     * Read data
     *
     * @param int $length
     * @throws \RuntimeException on connection errors
     */
    public function readBytes($length)
    {
        $temp = fread($this->fsock, $length);
        if (strlen($temp) != $length) {
            throw new \RuntimeException("Expected $length bytes; got " . strlen($temp));
        }
        return $temp;
    }
}
