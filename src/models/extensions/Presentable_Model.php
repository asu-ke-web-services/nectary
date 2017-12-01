<?php

namespace Nectary\Models\Extensions;

use Nectary\Models\Data_Model;
use Nectary\Factories\Factory;

/**
 * A Viewable model is one that can present itself so that all of its
 * properties and calculated properties are useable within a template
 */
abstract class Presentable_Model extends Data_Model {
  /**
   * Present the model using the class provided
   * by presents(). Present knows how to build
   * factories.
   *
   * @return array
   */
  public function present( $options = [] ) {
    $class_reference = $this->get_presenter_class_name();

    if ( ! is_null( $class_reference ) ) {
      $instance = new $class_reference( $this, $options );

      if ( $instance instanceof Factory ) {
        return $instance->build();
      } else {
        return to_array( $instance );
      }
    } else {
      return to_array( $this );
    }
  }

  /**
   * Called to determine which class knows how to present the model.
   * Returning the string of a Factory class is prefered.
   *
   * @return String The name of the class that knows how to present the model
   */
  abstract protected function get_presenter_class_name();
}
