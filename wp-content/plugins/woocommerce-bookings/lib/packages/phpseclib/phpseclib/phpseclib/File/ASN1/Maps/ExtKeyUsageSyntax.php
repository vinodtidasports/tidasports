<?php

/**
 * ExtKeyUsageSyntax
 *
 * PHP version 5
 *
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2016 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 *
 * Modified by woocommerce on 02-December-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Automattic\WooCommerce\Bookings\Vendor\phpseclib3\File\ASN1\Maps;

use Automattic\WooCommerce\Bookings\Vendor\phpseclib3\File\ASN1;

/**
 * ExtKeyUsageSyntax
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class ExtKeyUsageSyntax
{
    const MAP = [
        'type' => ASN1::TYPE_SEQUENCE,
        'min' => 1,
        'max' => -1,
        'children' => KeyPurposeId::MAP
    ];
}
