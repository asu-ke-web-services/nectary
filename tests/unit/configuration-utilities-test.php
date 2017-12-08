<?php

namespace Nectary;

use Nectary\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Test the configuration utilities in the framework
 *
 * @group configuration
 */
class Configuration_Utilities_Test extends TestCase {
	protected function setUp() {
		Configuration::reset();
	}

  protected static function get_mock_configuration() {
    Configuration::set_configuration_path();
    $instance = Configuration::get_instance();
    $instance->attributes = [ 'TEST' => 'Test Value' ];

    return $instance;
  }

	public function test_config_returns_correct_data() {
    self::get_mock_configuration();

		$value = config( 'TEST', 'default' );

		$this->assertEquals( 'Test Value', $value );
	}

	public function test_config_returns_correct_default() {
    self::get_mock_configuration();

		$value = config( 'WRONG', 'default' );

		$this->assertEquals( 'default', $value );
	}
}
