<?php

namespace Nectary\Factories;

use Nectary\Factories\Html_Factory;

class Html_Carousel_Factory extends Html_Factory {
  private $slides;
  private $indicators;
  private $id;

  public function __construct() {
    $this->id         = 'TODO';
    $this->indivators = '';
    $this->slides     = [];
    $this->classes    = '';
    $this->inner_classes = '';
  }

  public function add_slide( $html ) {
    $this->slides[] = $html;
  }

  public function add_class( $classes ) {
    $this->classes .= $classes;
  }

  public function add_inner_class( $classes ) {
    $this->inner_classes .= $classes;
  }


  public function turn_on_indicators() {
    // TODO
      // <ol class="carousel-indicators">
      //   <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
      //   <li data-target="#carousel-example-generic" data-slide-to="1"></li>
      //   <li data-target="#carousel-example-generic" data-slide-to="2"></li>
      // </ol>
  }

  public function build() {
    $html = join( $this->slides, '' );
    return 
    "<div id='{$this->id}' class='carousel slide {$this->classes}' data-ride='carousel'>
      {$this->indicators}
      <div class='carousel-inner {$this->inner_classes}' role='listbox'>
        {$html}
      </div>
    </div>";
  }
}
