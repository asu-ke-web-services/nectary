<?php

namespace Nectary\Utilities;

/**
 * Helper utility functions for dealing with JSON
 */
class Json_Utilities {
	/**
	 * Provide a path to the given json array:
	 *
	 * ```
	 * self::get( $json, 'key1.key2.key3' );
	 * ```
	 *
	 * This will return the value of however far it was
	 * able to go
	 *
	 * @param  array $json
	 * @param  string $path
	 * @return array|null
	 */
	public static function get( $json, $path ) {
		if ( self::check_path( $json, $path ) ) {
			$path_parts = explode( '.', $path );

			$current = $json;

			foreach ( $path_parts as $part ) {
				$current = $current[ $part ];
			}

			return $current;
		} else {
			return null;
		}
	}

	/**
	 * @param  array      $json
	 * @param  string     $path
	 * @param  null       $default
	 * @return array|null
	 */
	public static function get_or_default( $json, $path, $default = null ) {
		$ref = self::get( $json, $path );

		if ( $ref === null && ! self::check_path( $json, $path ) ) {
			return $default;
		} else {
			return $ref;
		}
	}

	public static function check_path( $json, $path ) {
		$path_parts = explode( '.', $path );

		$current = $json;

		foreach ( $path_parts as $part ) {
			if ( is_array( $current ) && array_key_exists( $part, $current ) ) {
				$current = $current[ $part ];
			} else {
				return false;
			}
		}

		return true;
	}
}
