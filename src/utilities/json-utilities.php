<?php

namespace Nectary\Utilities;

/**
 * Helper utility functions for dealing with JSON
 */
class Json_Utilities {
  public static function get( $json, $path ) {
    $path_parts = explode( '.', $path );

    $current = $json;

    foreach ( $path_parts as $part ) {
      if ( is_array( $current ) &&
           array_key_exists( $part, $current ) ) {
        $current = $current[ $part ];  
      } else {
        break;
      }
    }

    return $current;
  }

  public static function get_or_default( $json, $path, $default ) {
    $path_parts = explode( '.', $path );

    $current = $json;

    foreach ( $path_parts as $part ) {
      if ( is_array( $current ) &&
           array_key_exists( $part, $current ) ) {
        $current = $current[ $part ];  
      } else {
        return $default;
      }
    }

    return $current;
  }
}
