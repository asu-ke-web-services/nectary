<?php

/**
 * View Utility functions
 */

if ( ! function_exists( 'view' ) ) {
	/**
	 * Alias for new View_Factory
	 */
	function view( $template_name ) {
		return new \Nectary\Factories\View_Factory( $template_name );
	}
}

if ( ! function_exists( 'present' ) ) {
	/**
	 * To present $any thing, we recursively turn
	 * the object into a presentable state.
	 *
	 * Given an array, we iterate through it and
	 * resursively check if it is a Viewable_Model
	 * (which knows how to present itself) or an
	 * unpresentable object, which is simply returned.
	 *
	 * @param $any Mixed Attempts to present this object or array
	 * @param $options Array Passes these parameters when presenting the objects
	 * @return Mixed
	 */
	function present( $any, $options = [] ) {
		$presented = null;

		if ( is_array( $any ) ) {
			$presented = [];

			foreach ( $any as $part ) {
				$presented[] = present( $part, $options );
			}
		} elseif ( $any instanceof \Nectary\Models\Presentable_Model ) {
			$presented = $any->present( $options );
		} else {
			$presented = $any;
		}

		return $presented;
	}
}
