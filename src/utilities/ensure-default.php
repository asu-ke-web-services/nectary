<?php

if ( ! function_exists( 'ensure_default' ) ) {
	/**
	 * This ensures that the index of the array is defined but
	 * if its not it gets set to the default value
	 *
	 * @param  array      $options_array
	 * @param  string|int $index
	 * @param  mixed      $default_value
	 * @return array
	 */
	function ensure_default( array &$options_array, $index, $default_value ) {
		// if we're not passed an array, theres not much we can do
		if ( ! \is_array( $options_array ) ) {
			return array();
		}

		if ( ! isset( $options_array[ $index ] ) ) {
			$options_array[ $index ] = $default_value;
		}
	}
}
