<?php

namespace Nectary\Views;

use Nectary\Views\Handlebars_View;

/**
 * Simple implementation of the Handlebars_View
 */
class Simple_Handlebars_View extends Handlebars_View {
  protected $template_name;
  protected $callback;

  public function __construct( $view_root, $template_name, $callback ) {
    parent::__construct( null, $view_root );
    $this->template_name = $template_name;
    $this->callback      = $callback;
  }

  public function output() {
    $callback = $this->callback;

    return $callback( $this->engine );
  }
}
