<?php

namespace Nectary;

use Nectary\Singleton;
use Nectary\Exceptions\Invalid_Configuration_Exception;

class Configuration extends Singleton {
  public $attributes;

  public static function set_configuration_path( $path ) {
    self::set_instance( new self( $path ) );
  }

  public static function reset() {
    self::$instances[ self::class ] = null;
  }

  public function get( $key, $default = null ) {
    if ( array_key_exists( $key, $this->attributes ) ) {
      return $this->attributes[ $key ];
    } else {
      return $default;
    }
  }

  public function set( $key, $value ) {
    $this->attributes[ $key ] = $value;
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
   * TODO support different parsers
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

      if ( $this->is_valid_line( $parts ) ) {
        $key   = $parts[0];
        $value = $parts[1];

        $attributes[ $key ] = $value;
      } else {
        throw new Invalid_Configuration_Exception( 'The provided configuration is invalid' );
      }
    }

    return $attributes;
  }

  private function is_valid_line( $parts ) {
    return count( $parts ) === 2;
  }
}
