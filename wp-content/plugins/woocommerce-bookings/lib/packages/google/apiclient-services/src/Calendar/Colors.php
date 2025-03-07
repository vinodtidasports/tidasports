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

class Colors extends \Automattic\WooCommerce\Bookings\Vendor\Google\Model
{
  /**
   * @var ColorDefinition[]
   */
  public $calendar;
  protected $calendarType = ColorDefinition::class;
  protected $calendarDataType = 'map';
  /**
   * @var ColorDefinition[]
   */
  public $event;
  protected $eventType = ColorDefinition::class;
  protected $eventDataType = 'map';
  /**
   * @var string
   */
  public $kind;
  /**
   * @var string
   */
  public $updated;

  /**
   * @param ColorDefinition[]
   */
  public function setCalendar($calendar)
  {
    $this->calendar = $calendar;
  }
  /**
   * @return ColorDefinition[]
   */
  public function getCalendar()
  {
    return $this->calendar;
  }
  /**
   * @param ColorDefinition[]
   */
  public function setEvent($event)
  {
    $this->event = $event;
  }
  /**
   * @return ColorDefinition[]
   */
  public function getEvent()
  {
    return $this->event;
  }
  /**
   * @param string
   */
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  /**
   * @return string
   */
  public function getKind()
  {
    return $this->kind;
  }
  /**
   * @param string
   */
  public function setUpdated($updated)
  {
    $this->updated = $updated;
  }
  /**
   * @return string
   */
  public function getUpdated()
  {
    return $this->updated;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Colors::class, 'Google_Service_Calendar_Colors');
