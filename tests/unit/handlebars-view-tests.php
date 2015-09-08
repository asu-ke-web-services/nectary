<?php

namespace Nectary\Tests;

use Nectary\Views\Handlebars_View;

/**
 * Test the handlebars view class in the framework
 *
 * @group view
 */
class Handlebars_View_Test extends \PHPUnit_Framework_TestCase {
  function test_exists() {
    $this->assertEquals( 'Nectary\Views\Handlebars_View', Views\Handlebars_View::class );
  }
}
