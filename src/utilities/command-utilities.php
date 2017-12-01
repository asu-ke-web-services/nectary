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
	 *
	 * @param \Nectary\Commands\Command $command
	 * @return callback
	 */
	function dispatch( \Nectary\Commands\Command $command ) {
		return $command->handle();
	}
}
