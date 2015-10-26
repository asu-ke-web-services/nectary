<?php

namespace Nectary;

/**
 * Decorators provide a layer between an object that lets you
 * attach properties and methods to that object ar runtime.
 *
 * @todo support nested Decorators!
 */
abstract class Decorator {
  protected $object;

  public function __construct( $object ) {
    $this->object = $object;
  }

  public function __get( $var ) {
    if ( $method = $this->decorator_has_method( $var ) ) {
      return $method;
    }

    if ( is_array( $this->object ) ) {
      return $this->object[ $var ];
    } else {
      return $this->object->$var;
    }
  }

  public function __isset( $var ) {
    if ( $this->decorator_has_method( $var ) ) {
      return true;
    }

    if ( is_array( $this->object ) ) {
      return isset( $this->object[ $var ] );
    } else {
      return isset( $this->object->$var );
    }
  }

  /**
   * Pass any unknown methods through to the inject object.
   *
   * @param  string $method
   * @param  array  $arguments
   * @throws \BadMethodCallException
   * @return mixed
   */
  public function __call( $method, $arguments ) {
    if ( is_object( $this->object ) ) {
      $value = call_user_func_array(
          array(
            $this->object,
            $method,
          ),
          $arguments
      );

      return $value;
    }
    throw new \BadMethodCallException( "Method {$method} does not exist." );
  }

  private function decorator_has_method( $var ) {
    $no_dashes_or_spaces = str_replace( [ '-', ' ' ], '_', $var );

    $method_name = 'get_' . $no_dashes_or_spaces;

    if ( method_exists( $this, $method_name ) ) {
      return $this->$method_name();
    }
  }
}
