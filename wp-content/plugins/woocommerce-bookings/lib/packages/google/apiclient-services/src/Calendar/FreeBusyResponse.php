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

class FreeBusyResponse extends \Automattic\WooCommerce\Bookings\Vendor\Google\Model
{
  /**
   * @var FreeBusyCalendar[]
   */
  public $calendars;
  protected $calendarsType = FreeBusyCalendar::class;
  protected $calendarsDataType = 'map';
  /**
   * @var FreeBusyGroup[]
   */
  public $groups;
  protected $groupsType = FreeBusyGroup::class;
  protected $groupsDataType = 'map';
  /**
   * @var string
   */
  public $kind;
  /**
   * @var string
   */
  public $timeMax;
  /**
   * @var string
   */
  public $timeMin;

  /**
   * @param FreeBusyCalendar[]
   */
  public function setCalendars($calendars)
  {
    $this->calendars = $calendars;
  }
  /**
   * @return FreeBusyCalendar[]
   */
  public function getCalendars()
  {
    return $this->calendars;
  }
  /**
   * @param FreeBusyGroup[]
   */
  public function setGroups($groups)
  {
    $this->groups = $groups;
  }
  /**
   * @return FreeBusyGroup[]
   */
  public function getGroups()
  {
    return $this->groups;
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
  public function setTimeMax($timeMax)
  {
    $this->timeMax = $timeMax;
  }
  /**
   * @return string
   */
  public function getTimeMax()
  {
    return $this->timeMax;
  }
  /**
   * @param string
   */
  public function setTimeMin($timeMin)
  {
    $this->timeMin = $timeMin;
  }
  /**
   * @return string
   */
  public function getTimeMin()
  {
    return $this->timeMin;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(FreeBusyResponse::class, 'Google_Service_Calendar_FreeBusyResponse');
