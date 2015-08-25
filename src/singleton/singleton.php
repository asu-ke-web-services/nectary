<?php

namespace Nectary;

abstract class Singleton {
  protected static $instance = null;
  protected function __construct() { }

  protected function __clone() { }

  public static function get_instance(){
    if ( ! isset( static::$instance ) ) {
      static::$instance = new static;
    }
    return static::$instance;
  }
}
