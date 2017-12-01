<?php

/**
 * Configuration Utilities
 *
 * Contains alias functions
 */

use Nectary\Configuration\Configuration;

if ( ! function_exists( 'config' ) ) {
	/**
	 * Handle configuration call without knowing about how
	 * the Configuration object works
	 *
	 * @param $key
	 * @param null $default
	 * @return mixed
	 */
	function config( $key, $default = null ) {
		return Configuration::get_instance()->get( $key, $default );
	}
}
