<?php

namespace Nectary\Tests;

use Nectary\Router;

/**
 * Test the router class in the framework
 *
 * @group router
 */
class Router_Test extends \PHPUnit_Framework_TestCase {
  function test_exists() {
    $this->assertEquals( 'Nectary\Router', Router::class );
  }
}
