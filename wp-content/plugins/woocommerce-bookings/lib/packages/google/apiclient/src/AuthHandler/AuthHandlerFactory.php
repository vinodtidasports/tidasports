<?php
/**
 * Copyright 2015 Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Modified by woocommerce on 02-December-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Automattic\WooCommerce\Bookings\Vendor\Google\AuthHandler;

use Exception;
use Automattic\WooCommerce\Bookings\Vendor\GuzzleHttp\ClientInterface;

class AuthHandlerFactory
{
    /**
     * Builds out a default http handler for the installed version of guzzle.
     *
     * @return Guzzle6AuthHandler|Guzzle7AuthHandler
     * @throws Exception
     */
    public static function build($cache = null, array $cacheConfig = [])
    {
        $guzzleVersion = null;
        if (defined('\Automattic\WooCommerce\Bookings\Vendor\GuzzleHttp\ClientInterface::MAJOR_VERSION')) {
            $guzzleVersion = ClientInterface::MAJOR_VERSION;
        } elseif (defined('\Automattic\WooCommerce\Bookings\Vendor\GuzzleHttp\ClientInterface::VERSION')) {
            $guzzleVersion = (int) substr(ClientInterface::VERSION, 0, 1);
        }

        switch ($guzzleVersion) {
            case 6:
                return new Guzzle6AuthHandler($cache, $cacheConfig);
            case 7:
                return new Guzzle7AuthHandler($cache, $cacheConfig);
            default:
                throw new Exception('Version not supported');
        }
    }
}
