<?php

namespace Nectary\Tests\Integration;

use Nectary\Factories\View_Factory;
use Nectary\Configuration;
/**
 * @group integration
 * @group view
 */
class View_Factory_Test extends \PHPUnit_Framework_TestCase {
  function test_view_factory_creates_handlebars_output() {
    $view_factory = new View_Factory( 'test' );

    Configuration::get_instance()->set(
      'path_to_views',
      dirname( __DIR__ ) . '/support/views'
    );

    $view = $view_factory->build();

    $this->assertEquals( 'I am handlebars!', $view->content );
  }
}