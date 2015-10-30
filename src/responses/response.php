<?php

namespace Nectary;

use Nectary\Data_Model;

/**
 * View Model for head, content, and footer data
 *
 * Extends Data_Model for the magic setters and
 * getters.
 */
class Response extends Data_Model {
  public static $error_404;

  public $http_header;
  /**
   * An array that can contain:
   * - no_cache [Boolean]
   * - title [String]
   * - description [String]
   * - canonical [String]
   * - open_graph_image [String] - a path
   */
  public $head;
  public $content;
  public $footer;
  /**
   * Contains extra data, like:
   * - web_standards_hero_image [String] - a path
   */
  public $extra;
  public $error = false;
  public $is_singular;
}

Response::$error_404 = new Response(
    array(
      'http_header' => array( 'HTTP/1.0 404 Not Found - Archive Empty' ),
      'content' => 'Not Found',
    )
);
