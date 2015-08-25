<?php

namespace Nectary;

use Nectary\Singleton;
use Nectary\Exceptions\Invalid_Configuration_Exception;

class Configuration extends Singleton {
  public $attributes;

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
  protected function __construct( $path = '.env' ) {
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
