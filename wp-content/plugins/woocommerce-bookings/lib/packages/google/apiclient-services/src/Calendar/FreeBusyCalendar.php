<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 *
 * Modified by woocommerce on 02-December-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Automattic\WooCommerce\Bookings\Vendor\Google\Service\Calendar;

class FreeBusyCalendar extends \Automattic\WooCommerce\Bookings\Vendor\Google\Collection
{
  protected $collection_key = 'errors';
  /**
   * @var TimePeriod[]
   */
  public $busy;
  protected $busyType = TimePeriod::class;
  protected $busyDataType = 'array';
  /**
   * @var Error[]
   */
  public $errors;
  protected $errorsType = Error::class;
  protected $errorsDataType = 'array';

  /**
   * @param TimePeriod[]
   */
  public function setBusy($busy)
  {
    $this->busy = $busy;
  }
  /**
   * @return TimePeriod[]
   */
  public function getBusy()
  {
    return $this->busy;
  }
  /**
   * @param Error[]
   */
  public function setErrors($errors)
  {
    $this->errors = $errors;
  }
  /**
   * @return Error[]
   */
  public function getErrors()
  {
    return $this->errors;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(FreeBusyCalendar::class, 'Google_Service_Calendar_FreeBusyCalendar');
