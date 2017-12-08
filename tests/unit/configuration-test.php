<?php

namespace Nectary;

use Nectary\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Test the configuration class in the framework
 *
 * @group configuration
 * @group singleton
 */
class Configuration_Test extends TestCase {
	protected function setUp() {
		Configuration::reset();
	}

	protected static function get_mock_configuration() {
    Configuration::set_configuration_path();
    $instance = Configuration::get_instance();
    $instance->attributes = [ 'TEST' => 'Test Value' ];

    return $instance;
  }

	public function test_is_singleton_instance() {
    $instance_1 = self::get_mock_configuration();
		$instance_2 = Configuration::get_instance();

		$this->assertEquals( $instance_1, $instance_2 );
	}

	public function test_returns_requested_value() {
    $instance = self::get_mock_configuration();

		$value = $instance->get( 'TEST' );

		$this->assertEquals( 'Test Value', $value );
	}

	public function test_returns_default_value() {
    $instance = self::get_mock_configuration();

		$value = $instance->get( 'nope', 'default' );

		$this->assertEquals( 'default', $value );
	}

	public function test_empty_values_are_valid() {
    $instance = self::get_mock_configuration();

		$value = $instance->get( 'test', '' );

		$this->assertEquals( '', $value );
	}

	/**
	 * DISABLED: Not sure how to test just Nectary, since parsing of the .env file is now
	 * handled by dotenv plugin.
	 */
//	public function test_throws_exception_when_loading_invalid_configuration() {
//		$this->expectException( 'Nectary\Exceptions\Invalid_Configuration_Exception' );
//		$thrown = false;
//
//		try {
//      $instance = Configuration::get_instance();
//		} catch (Exception $e) {
//			$thrown = true;
//		}
//
//		$this->assertTrue( $thrown, 'Exception should be thrown' );
//	}

	public function test_configuration_can_set_value() {
    $instance = self::get_mock_configuration();

		$instance->set( 'newkey', 'newvalue' );
		$this->assertEquals( 'newvalue', $instance->get( 'newkey' ) );
	}

	public function test_configuration_can_add_single_value() {
    $instance = self::get_mock_configuration();

		$instance->add( 'newkey', 'newvalue' );
		$this->assertEquals( 'newvalue', $instance->get( 'newkey' ) );
	}

	public function test_configuration_can_add_multiple_values() {
    $instance = self::get_mock_configuration();

		$instance->add( 'newkey', 'newvalue' );
		$instance->add( 'newkey', 'anothervalue' );
		$instance->add( 'newkey', 'andanother' );
		$this->assertEquals( array( 'newvalue', 'anothervalue', 'andanother' ),
				$instance->get( 'newkey' ) );
	}

}
