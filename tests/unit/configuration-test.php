<?php

namespace Nectary;

use Nectary\Configuration;

/**
 * Test the configuration class in the framework
 *
 * @group configuration
 * @group singleton
 */
class Configuration_Test extends \PHPUnit_Framework_TestCase {
  function setUp() {
    Configuration::reset();
  }

  function test_is_singleton_instance() {
    $instance_1 = Configuration::get_instance();
    $instance_2 = Configuration::get_instance();

    $this->assertEquals( $instance_1, $instance_2 );
  }

  function test_loads_in_data_from_file() {
    $mock = create_function_mock( $this, 'file_exists', 1 );
    $mock->will( $this->returnValue( true ) );

    $mock = create_function_mock( $this, 'file_get_contents', 1 );
    $mock->will( $this->returnValue( 'test=My Special Value' ) );

    $instance = Configuration::get_instance();

    $value = $instance->get( 'test' );

    $this->assertEquals( 'My Special Value', $value );
  }

  function test_returns_default_value() {
    $mock = create_function_mock( $this, 'file_exists', 1 );
    $mock->will( $this->returnValue( true ) );

    $mock = create_function_mock( $this, 'file_get_contents', 1 );
    $mock->will( $this->returnValue( 'test=My Special Value' ) );

    $instance = Configuration::get_instance();

    $value = $instance->get( 'nope', 'default' );

    $this->assertEquals( 'default', $value );
  }

  function test_set_configuration_path_overrides_attributes() {
    $mock = create_function_mock( $this, 'file_exists', 2 );
    $mock->will( $this->onConsecutiveCalls( false, true ) );

    $mock = create_function_mock( $this, 'file_get_contents', 1 );
    $mock->with( $this->stringContains( '.newenv' ) )
    ->will( $this->returnValue( 'test=bang' ) );

    $instance = Configuration::get_instance();
    Configuration::set_configuration_path( '.newenv' );
    $instance = Configuration::get_instance();

    $value = $instance->get( 'test', 'default' );

    $this->assertEquals( 'bang', $value );
  }

  function test_empty_values_are_valid() {
    $mock = create_function_mock( $this, 'file_exists', 1 );
    $mock->will( $this->returnValue( true ) );

    $mock = create_function_mock( $this, 'file_get_contents', 1 );
    $mock->will( $this->returnValue( 'test=' ) );

    $instance = Configuration::get_instance();

    $value = $instance->get( 'test', 'default' );

    $this->assertEquals( '', $value );
  }

  function test_throws_exception_when_loading_invalid_configuration() {
    $this->setExpectedException( 'Nectary\Exceptions\Invalid_Configuration_Exception' );

    $mock = create_function_mock( $this, 'file_exists', 1 );
    $mock->will( $this->returnValue( true ) );

    $mock = create_function_mock( $this, 'file_get_contents', 1 );
    $mock->will( $this->returnValue( 'invalid' ) );

    $thrown = false;

    try {
      $instance = Configuration::get_instance();
    } catch (Exception $e) {
      $thrown = true;
    }

    $this->assertTrue( $thrown, 'Exception should be thrown' );
  }

  function test_multiline_configuration() {
    $mock = create_function_mock( $this, 'file_exists', 1 );
    $mock->will( $this->returnValue( true ) );

    $mock = create_function_mock( $this, 'file_get_contents', 1 );
    $mock->will( $this->returnValue( "test=My Special Value\nanother=1234" ) );

    $instance = Configuration::get_instance();

    $value = $instance->get( 'test' );
    $this->assertEquals( 'My Special Value', $value );
    $value = $instance->get( 'another' );
    $this->assertEquals( '1234', $value );
  }

  function test_configuration_can_set_value() {
    Configuration::get_instance()->set( 'newkey', 'newvalue' );
    $this->assertEquals( 'newvalue', Configuration::get_instance()->get( 'newkey' ) );
  }
}
