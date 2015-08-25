<?php

namespace Nectary;

use Nectary\Exceptions\Invalid_Configuration_Exception;

class Configuration {
  private static $instance;

  public $attributes;

  public static function get_instance( $path = '.env' ) {
    if ( isset( self::$instance ) ) {
      $instance = self::$instance;
    } else {
      $instance = new Configuration( $path );
      self::$instance = $instance;
    }

    return $instance;
  }

  public static function set_configuration_path( $path ) {
    self::$instance = new Configuration( $path );
  }

  public static function reset() {
    self::$instance = null;
  }

  public function get( $key, $default = null ) {
    if ( array_key_exists( $key, $this->attributes ) ) {
      return $this->attributes[ $key ];
    } else {
      return $default;
    }
  }

  /**
   * Load in the configuration file and parse it
   */
  private function __construct( $path ) {
    $this->attributes = [];
    $configuration = '';

    if ( file_exists( $path ) ) {
      $configuration = @file_get_contents( $path );  
    }

    if ( ! empty( $configuration ) ) {
      $this->attributes = $this->parse( $configuration );
    }
  }

  /**
   * Parse the configuration text
   *
   * @throws \Nectary\Exceptions\Invalid_Configuration_Exception
   */
  private function parse( $configuration ) {
    $attributes = [];
    // Split on new line
    $lines = preg_split ('/$\R?^/m', $configuration );

    // Split each line by the first equal sign
    foreach ( $lines as $line ) {
      $parts = preg_split( '/[=]/', $line, 2 );

      if ( count( $parts ) < 2 ) {
        throw new Invalid_Configuration_Exception( 'The provided configuration is invalid' );
      }

      $key   = $parts[0];
      $value = $parts[1];

      $attributes[ $key ] = $value;
    }

    return $attributes;
  }
}
