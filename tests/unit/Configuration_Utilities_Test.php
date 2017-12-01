<?php

namespace Nectary;

use Nectary\Configuration\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Test the configuration utilities in the framework
 *
 * @group configuration
 */
class Configuration_Utilities_Test extends TestCase {
  function setUp() {
    Configuration::reset();
  }

  function test_config_returns_corrent_data() {
    $mock = create_function_mock( $this, 'file_exists', 1 );
    $mock->will( $this->returnValue( true ) );

    $mock = create_function_mock( $this, 'file_get_contents', 1 );
    $mock->will( $this->returnValue( 'key=1234' ) );

    $value = config( 'key', 'default' );

    $this->assertEquals( '1234', $value );
  }

  function test_config_returns_correct_default() {
    $mock = create_function_mock( $this, 'file_exists', 1 );
    $mock->will( $this->returnValue( true ) );

    $mock = create_function_mock( $this, 'file_get_contents', 1 );
    $mock->will( $this->returnValue( 'key=1234' ) );

    $value = config( 'wrong_key', 'default' );

    $this->assertEquals( 'default', $value );
  }
}
