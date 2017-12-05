<?php

namespace Nectary\Tests\Integration;

use Nectary\Factories\View_Factory;
use Nectary\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * @group integration
 * @group view
 */
class View_Factory_Test extends TestCase {
  public function test_view_factory_creates_handlebars_output() {
    $view_factory = new View_Factory( 'test' );

    Configuration::get_instance()->set(
        'path_to_views',
        dirname( __DIR__ ) . '/support/views'
    );

    $view = $view_factory->build();

    $this->assertEquals( 'I am handlebars!', $view->content );
  }

  public function test_view_factory_creates_handlebars_output_with_custom_view_path() {
    Configuration::get_instance()->reset();

    $view_factory = new View_Factory( 'test', dirname( __DIR__ ) . '/support/views' );

    $view = $view_factory->build();

    $this->assertEquals( 'I am handlebars!', $view->content );
  }
}
