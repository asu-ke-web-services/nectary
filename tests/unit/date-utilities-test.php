<?php

namespace Nectary\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @group utility
 */
class Date_Utilities_Test extends TestCase {

  protected function setUp() {
    // Our test cases assume a UTC timezone is defined
    date_default_timezone_set( 'UTC' );
  }

  function test_date_turns_into_correct_calendar_representation() {
    $timestamp     = '1441746685';
    $expected_date = '20150908T211125';

    $this->assertEquals( $expected_date, date_to_cal( $timestamp ) );
  }

  function test_date_to_calendar_can_accept_no_arguments() {
    $this->assertNotEmpty( date_to_cal() );
  }
}
