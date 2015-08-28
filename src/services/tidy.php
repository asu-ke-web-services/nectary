<?php

namespace Nectary\Services;

class Tidy {
  public function excerpt( $content_excerpt, $words = 50 ) {
    $content = self::normalize_html_string( $content_excerpt );
    $content = trim( $content );
    // If we only have 1 paragraph and less than $words words, reset the content
    // to the full event content
    if ( count( explode( ' ', $content ) ) < $words ) {
        return $content;
    } else {
      // We have some trimming to do
      $content = implode(
          ' ',
          array_slice( explode( ' ', $content ), 0, $words )
      );
      $content = trim( $content );
      if ( substr( $content, -1 ) == '.' ) {
        $content .= '..';
      } else {
        $content .= '...';
      }
    }

    // TODO implement a better fallback
    if ( ! function_exists( 'tidy_parse_string' ) ) {
      error_log( 'Missing tidy_parse_string library... '
          . 'Falling back to nothing'
      );
      return $content;
    }

    // Fix any markup we destroyed
    $tidy_config = array(
     'clean'          => true,
     'output-xhtml'   => true,
     'show-body-only' => true,
     'wrap'           => 0,
    );

    $tidy = tidy_parse_string( $content, $tidy_config, 'UTF8' );
    $tidy->cleanRepair();
    return '' . $tidy;
  }
  
  public function normalize_html_string( $input ) {
    // Strip HTML Tags
    $clear = strip_tags( $input );
    // Clean up things like &amp;
    $clear = html_entity_decode( $clear );
    // Strip out any url-encoded stuff
    $clear = urldecode( $clear );
    // Replace Multiple spaces with single space
    $clear = preg_replace( '/\s+/', ' ', $clear );
    // Trim the string of leading/trailing space
    $clear = trim( $clear );
    return $clear;
  }
}