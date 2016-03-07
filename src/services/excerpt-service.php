<?php

namespace Nectary\Services;

use Coduo\PHPHumanizer\StringHumanizer as Truncate;

/**
 * Service for using PHP Humanizer
 *
 * Use to abstract away how to get excerpts using
 * the Humanizer library.
 */
class Excerpt_Service {
  public function excerpt( $content_excerpt, $words = 50 ) {
    $content = $this->normalize_html_string( $content_excerpt );

    $content = trim( $content );
    // If we only have 1 paragraph and less than $words words, reset the content
    // to the full event content
    if ( count( explode( ' ', $content ) ) <= $words ) {
      return $content;
    } else {
      // We have some trimming to do
      $content = implode(
          ' ',
          array_slice( explode( ' ', $content ), 0, $words )
      );

      $length = strlen( $content );

      $content = Truncate::truncateHtml( $content_excerpt, $length );

      if ( substr( $content, -1 ) == '.' ) {
        $content .= '..';
      } else {
        $content .= '...';
      }
    }

    return $content;
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
