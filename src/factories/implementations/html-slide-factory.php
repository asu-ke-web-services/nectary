<?php

namespace Nectary\Factories;

use Nectary\Factories\Html_Factory;

class Html_Slide_Factory extends Html_Factory {
  private $active;
  private $classes;

  public function __construct() {
    $this->active = false;
    $this->classes = '';
  }

  public function is_active() {
    $this->active = true;
  }

  public function add_class( $classes ) {
    $this->classes .= $classes;
  }

  public function build() {
    $classes = ( $this->active ) ? 'active' : '';

    $classes .= ' ' . $this->classes;

    return "<div class='item {$classes}'>
      {$this->html}
    </div>";
  }
}
