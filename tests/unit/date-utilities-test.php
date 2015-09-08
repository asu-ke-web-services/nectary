<?php

namespace Nectary\Tests;

/**
 * @group utility
 */
class Date_Utilities_Test extends \PHPUnit_Framework_TestCase {
  function test_date_turns_into_correct_calendar_representation() {
    $timestamp     = '1441746685';
    $expected_date = '20150908T211125';

    $this->assertEquals( $expected_date, date_to_cal( $timestamp ) );
  }
}
