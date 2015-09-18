<?php

namespace Nectary;

abstract class Singleton {
  protected static $instances = array();
  protected function __construct() {}
  protected function __clone() {}
  public function __wakeup() {
    throw new \Exception( 'Cannot unserialize singleton' );
  }

  public static function get_instance() {
    $cls = get_called_class(); // late-static-bound class name
    if ( ! isset( self::$instances[ $cls ] ) ) {
      self::$instances[ $cls ] = new static;
    }

    return self::$instances[ $cls ];
  }

  public static function set_instance( $instance ) {
    $cls = get_called_class();

    self::$instances[ $cls ] = $instance;
  }
}
