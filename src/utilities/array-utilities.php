<?php

if ( ! function_exists( 'array_peek' ) ) {
	function array_peek( $array ) {
		if ( \is_array( $array ) ) {
			return reset( $array );
		}

		return null;
	}
}


if ( ! function_exists( 'to_array' ) ) {
	/**
	 * Wraps whatever argument its passed in an array unless it is an array, always returns an array. always.
	 *
	 * @param  mixed $something gets wrapped in an array
	 * @return array always returns an array
	 */
	function to_array( $something = array() ) : array {
		if ( \is_array( $something ) ) {
			return $something;
		}

		if ( null === $something ) {
			return array();
		}

		return array( $something );
	}
}
