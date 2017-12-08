<?php

namespace Nectary;

use Nectary\Singleton;
use Nectary\Exceptions\Invalid_Configuration_Exception;

/**
 * Configuration Singleton
 *
 * House global configuration settings here. The Configuration
 * object can load in a configuration .env file, or have
 * attributes added to it programmatically.
 *
 * @extends Singleton
 */
class Configuration extends Singleton {
	public $attributes;

	public static function set_configuration_path( $path ) {
		self::set_instance( new self( $path ) );
	}

	public static function reset() {
		self::$instances[ self::class ] = null;
	}

	public function get( $key, $default = null ) {
		if ( array_key_exists( $key, $this->attributes ) ) {
			return $this->attributes[ $key ];
		} else {
			return $default;
		}
	}

	public function set( $key, $value ) {
		$this->attributes[ $key ] = $value;
	}

	/**
	 * Add to an new or existing value.
	 * This will promote scalor values into an array to contain multiple values.
	 */
	public function add( $key, $value ) {
		if ( ! array_key_exists( $key, $this->attributes ) ) {
			$this->attributes[ $key ] = $value;
		} else {
			if ( ! is_array( $this->attributes[ $key ] ) ) {
				$this->attributes[ $key ] = array( $this->attributes[ $key ], $value );
			} else {
				array_push( $this->attributes[ $key ], $value );
			}
		}
	}

	/**
	 * Load the configuration dotenv file
	 *
	 * @param string $path
	 * @throws Invalid_Configuration_Exception
	 */
	protected function __construct( $path = '.env' ) {
		$this->attributes = [];
		$configuration    = [];

		if ( file_exists( $path ) ) {
			$configuration = ( new \josegonzalez\Dotenv\Loader( $path ) )->parse()->toArray();
		}

		if ( ! is_array( $configuration ) ) {
			throw new Invalid_Configuration_Exception( 'The provided configuration is invalid' );
		}

		if ( ! empty( $configuration ) ) {
			$this->attributes = $configuration;
		}
	}
}
