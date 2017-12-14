<?php

namespace Nectary;

/**
 * Support singletons in PHP
 */
abstract class Singleton {
	protected static $instances = array();

	/**
	 * Singleton constructor.
	 *
	 * @param array|string|null $options
	 */
	protected function __construct( $options = array() ) {}
	protected function __clone() {}

	/**
	 * @param array|string|null $options
	 * @return mixed
	 */
	public static function get_instance( $options = array() ) {
		$cls = static::class; // late-static-bound class name
		if ( ! isset( self::$instances[ $cls ] ) ) {
			self::$instances[ $cls ] = new static( $options );
		}

		return self::$instances[ $cls ];
	}

	public static function set_instance( $instance ) {
		$cls = static::class;

		self::$instances[ $cls ] = $instance;
	}
}
