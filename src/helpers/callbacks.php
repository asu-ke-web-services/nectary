<?php

namespace Nectary\Helpers;

use Nectary\Response;

trait Callbacks {
  public static function error() {
    return Response::$error_404;
  }

  public static function false() {
    return false;
  }

  public static function rss_error() {
    // TODO rss xml error
    return '';
  }

  public static function empty_string() {
    return '';
  }
}