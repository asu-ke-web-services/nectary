<?php

namespace Nectary\Tests;

use Nectary\Decoratable;
use Nectary\Tests\Mocks\Decoratable_Test_Object;
use PHPUnit\Framework\TestCase;

/**
 * Test the decoratable interface in the framework
 *
 * @group decorator
 */
class Decoratable_Test extends TestCase {
  public function test_exists() {
    $this->assertEquals( 'Nectary\Decoratable', Decoratable::class );
  }

  public function test_decoratable_classes_can_be_decorated() {
    $base = new Decoratable_Test_Object();
    $decorated = $base->decorate();

    $this->assertInstanceof( 'Nectary\Decoratable', $base );
    $this->assertInstanceof( 'Nectary\Decorator', $decorated );
  }
}
