<?php

namespace Nectary\Tests;

use Nectary\Decoratable;
use Nectary\Tests\Mocks\Decoratable_Test_Object;

/**
 * Test the decoratable interface in the framework
 *
 * @group decorator
 */
class Decoratable_Test extends \PHPUnit_Framework_TestCase {
  function test_exists() {
    $this->assertEquals( 'Nectary\Decoratable', Decoratable::class );
  }

  function test_decoratable_classes_can_be_decorated() {
    $base = new Decoratable_Test_Object();
    $decorated = $base->decorate();

    $this->assertInstanceof( 'Nectary\Decoratable', $base );
    $this->assertInstanceof( 'Nectary\Decorator', $decorated );
  }
}
