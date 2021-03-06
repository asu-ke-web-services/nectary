<?php

namespace Nectary;

use josegonzalez\Dotenv\Loader;

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

	public static function set_configuration_path( $path = null ) {
		self::set_instance( new self( $path ) );
	}

	public static function reset() {
		self::$instances[ self::class ] = null;
	}

	public function get( $key, $default = null ) {
		if ( array_key_exists( $key, $this->attributes ) ) {
			return $this->attributes[ $key ];
		}

		return $default;
	}

	public function set( $key, $value ) {
		$this->attributes[ $key ] = $value;
	}

	/**
	 * Add to an new or existing value.
	 * This will promote scalar values into an array to contain multiple values.
	 *
	 * @param string $key
	 * @param mixed  $value
	 */
	public function add( $key, $value ) {
		if ( ! array_key_exists( $key, $this->attributes ) ) {
			$this->attributes[ $key ] = $value;
		} else {
			if ( ! \is_array( $this->attributes[ $key ] ) ) {
				$this->attributes[ $key ] = array( $this->attributes[ $key ], $value );
			} else {
				$this->attributes[ $key ][] = $value;
			}
		}
	}

	/**
	 * Load the configuration dotenv values into the Configuration instance.
	 * By intention, we are not storing these values in environment variables.
	 * Instead, they are stored in the Configuration instance and likewise retrieved
	 * from there.
	 *
	 * @param string $path
	 * @throws Invalid_Configuration_Exception
	 */
	protected function __construct( $path = null ) {
		Singleton::__construct();
		$this->attributes = [];
		$configuration    = [];

		if ( null !== $path && file_exists( $path ) ) {
			try {
				$configuration = ( new Loader( $path ) )->parse()->toArray();
			} catch ( \M1\Env\Exception\ParseException $exception ) {
				throw new Invalid_Configuration_Exception( 'The provided configuration is invalid' );
			}
		}

		if ( ! empty( $configuration ) ) {
			$this->attributes = $configuration;
		}
	}
}
