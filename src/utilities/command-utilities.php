<?php

/**
 * Command Utilities
 *
 * Contains alias functions
 */

if ( ! function_exists( 'dispatch' ) ) {
	/**
	 * Handle a dispatch without knowing about the command's
	 * details
	 */
	function dispatch( \Nectary\Command $command ) {
		return $command->handle();
	}
}
