<?php

namespace Nectary\Models;

use Nectary\Data_Model;
use Nectary\Factory;

/**
 * A Viewable model is one that can present itself so that all of its
 * properties and calculated properties are usable within a template
 */
abstract class Presentable_Model extends Data_Model {
	/**
	 * Present the model using the class provided
	 * by presents(). Present knows how to build
	 * factories.
	 *
	 * @param  array $options
	 * @return mixed
	 */
	public function present( $options = [] ) {
		$class_reference = $this->get_presenter_class_name();

		if ( null !== $class_reference ) {
			$instance = new $class_reference( $this, $options );

			if ( $instance instanceof Factory ) {
				return $instance->build();
			}

			return to_array( $instance );
		}

		return to_array( $this );
	}

	/**
	 * Called to determine which class knows how to present the model.
	 * Returning the string of a Factory class is preferred.
	 *
	 * @return string The name of the class that knows how to present the model
	 */
	abstract protected function get_presenter_class_name();
}
