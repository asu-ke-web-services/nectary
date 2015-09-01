<?php

if ( ! function_exists( 'starts_with' ) ) {
  /**
   * Alias
   */
  function starts_with( $haystack, $needle ) {
    return ! strncmp( $haystack, $needle, strlen( $needle ) );
  }
}

if ( ! function_exists( 'ends_with' ) ) {
  /**
   * Alias
   */
  function ends_with( $haystack, $needle ) {
    $haystack_length = strlen( $haystack );
    $needle_length   = strlen( $needle );
    if ( $needle_length > $haystack_length )
      return false;
    return substr_compare( $haystack, $needle, -$needle_length ) === 0;
  }
}