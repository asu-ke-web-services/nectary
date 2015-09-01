<?php

namespace Nectary\Factories;

use Nectary\Factories\Html_Factory;

class Html_Carousel_Factory extends Html_Factory {
  private $slides;
  private $indicators;
  private $id;

  public function __construct() {
    $this->id            = uniqid( 'carousel-' );
    $this->indicators    = false;
    $this->slides        = [];
    $this->classes       = '';
    $this->inner_classes = '';
  }

  public function add_slide( $html ) {
    $this->slides[] = $html;
  }

  public function add_class( $classes ) {
    $this->classes .= ' ' . $classes;
  }

  public function add_inner_class( $classes ) {
    $this->inner_classes .= ' ' . $classes;
  }

  public function turn_on_indicators() {
    $this->indicators = true;
  }

  public function build() {
    $html = join( $this->slides, '' );

    if ( $this->indicators ) {
      $indicators = $this->build_indicators();
    } else {
      $indicators = '';
    }

    return 
    "<div id='{$this->id}' class='carousel slide {$this->classes}' data-ride='carousel'>
      {$this->indicators}
      <div class='carousel-inner {$this->inner_classes}' role='listbox'>
        {$html}
        {$indicators}
      </div>
    </div>";
  }

  private function build_indicators() {
    $number = count( $this->slides );
    $inner_indicators = '';

    for ( $i = 0; $i < $number; $i++ ) {
      $classes = ( $i === 0 ) ? 'active' : '';
      $inner_indicators .= "
        <li data-target='#{$this->id}' data-slide-to='{$i}' class='{$classes}'></li>
      ";
    }

    return "
      <ol class='carousel-indicators'>
        {$inner_indicators}
      </ol>
    ";
  }
}
