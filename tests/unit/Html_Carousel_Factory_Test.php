<?php

namespace Nectary\Tests;

use Nectary\Factories\Implementations\Html_Carousel_Factory;
use PHPUnit\Framework\TestCase;

/**
 * Test the html carouel factory
 *
 * @group html
 * @group factory
 */
class Html_Carousel_Factory_Test extends TestCase {
  function test_basic_carousel() {
    $factory = new Html_Carousel_Factory();

    $html = $factory->build();

    $this->assertContains( 'div', $html );
    $this->assertContains( 'slide', $html );
    $this->assertContains( 'carousel', $html );
    $this->assertContains( 'carousel-inner', $html );
  }

  function test_carousel_markup_strict() {
    $factory = new Html_Carousel_Factory();

    $html = $factory->build();

    $this->assertContains( '<div id=', $html );
    $this->assertContains( "class='carousel slide ", $html );
    $this->assertContains( "data-ride='carousel'", $html );
    $this->assertContains( "<div class='carousel-inner ", $html );
    $this->assertContains( "role='listbox'>", $html );
    $this->assertContains( '</div>', $html );
  }

  function test_carousel_has_custom_classes() {
    $factory = new Html_Carousel_Factory();
    $factory->add_class( 'wow' );
    $factory->add_class( 'gios' );
    $html = $factory->build();

    $this->assertContains( 'wow', $html );
    $this->assertContains( 'gios', $html );
    $this->assertNotContains( 'wowgios', $html );
    $this->assertNotContains( 'gioswow', $html );
  }

  function test_carousel_has_custom_inner_classes() {
    $factory = new Html_Carousel_Factory();
    $factory->add_inner_class( 'wow' );
    $factory->add_inner_class( 'gios' );
    $html = $factory->build();

    $this->assertContains( 'wow', $html );
    $this->assertContains( 'gios', $html );
    $this->assertNotContains( 'wowgios', $html );
    $this->assertNotContains( 'gioswow', $html );
  }

  function test_carousel_has_custom_data_attributes() {
    $factory = new Html_Carousel_Factory();
    $factory->add_data_attributes( 'wow' );
    $factory->add_data_attributes( 'gios' );
    $html = $factory->build();

    $this->assertContains( 'wow', $html );
    $this->assertContains( 'gios', $html );
    $this->assertNotContains( 'wowgios', $html );
    $this->assertNotContains( 'gioswow', $html );
  }

  function test_carousel_can_have_indicators() {
    $factory = new Html_Carousel_Factory();
    $factory->turn_on_indicators();
    $html = $factory->build();

    $this->assertContains( "<ol class='carousel-indicators'>", $html );
  }

  function test_carousel_can_have_slides() {
    $factory = new Html_Carousel_Factory();
    $factory->add_slide( '<span>wow</span>' );
    $html = $factory->build();

    $this->assertContains( '<span>wow</span>', $html );
  }

  function test_carousel_can_have_slides_and_indicators() {
    $factory = new Html_Carousel_Factory();
    $factory->turn_on_indicators();
    $factory->add_slide( '<span>wow</span>' );
    $factory->add_slide( '<span>wow</span>' );
    $html = $factory->build();

    $this->assertContains( '<span>wow</span>', $html );
    $this->assertContains( '<li data-target=', $html );
    $this->assertContains( 'active', $html );
  }
}
