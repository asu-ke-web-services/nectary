<?php

/**
 * Date Utilities
 */

if ( ! function_exists( 'date_to_cal' ) ) {
	/**
	 * Date a string and transform it to a string of the form Ymd\THis.
	 *
	 * @param string $timestamp
	 * @return string
	 */
	function date_to_cal( $timestamp = '' ) {
		if ( empty( $timestamp ) ) {
			return date( 'Ymd\THis' );
		}

		return date( 'Ymd\THis', $timestamp );
	}
}
