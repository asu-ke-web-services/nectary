<?php

namespace Nectary\Helpers;

use Nectary\Response;

/**
 * Trait Callbacks - Common place for list of all reusable callbacks
 */
trait Callbacks {

  /**
   * To respond client with 404 error
   *
   * @return Response::$error_404
   */
  public static function not_found_error() {
    return Response::$error_404;
  }

  /**
   * Always return false
   *
   * @return false
   */
  public static function false() {
    return false;
  }

  /**
   * Any errors while parsind rss currently just returns empty string always
   *
   * @return String ''
   */
  public static function rss_error() {
    // TODO rss xml error
    return '';
  }

  /**
   * Always returns empty string
   *
   * @return String ''
   */
  public static function empty_string() {
    return '';
  }
}
