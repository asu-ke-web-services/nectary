<?php

namespace Nectary\Tests;

use Nectary\Decorators\Decoratable;
use Nectary\Decorators\Decorator;
use PHPUnit\Framework\TestCase;

/**
 * Test the decoratable interface in the framework
 *
 * @group decorator
 */
class Decorator_Test extends TestCase {
  function test_exists() {
    $this->assertEquals( 'Nectary\Decorator', Decorator::class );
  }

  function test_decorator_provides_access_to_base_class() {
    $decoratable = new Decoratable_Test_Object();

    $decorated = $decoratable->decorate();

    $this->assertEquals( 'first_name', $decorated->first_name );
  }

  function test_decorator_provides_functional_shortcut() {
    $decoratable = new Decoratable_Test_Object();

    $decorated = $decoratable->decorate();

    $this->assertEquals( 'full_name', $decorated->name );
  }

  function test_decorator_provides_isset_functionality() {
    $decoratable = new Decoratable_Test_Object();

    $decorated = $decoratable->decorate();

    $this->assertTrue( isset( $decorated->name ) );
    $this->assertFalse( isset( $decorated->full_name ) );
  }
}

class Decorator_Test_Object extends Decorator {
  public function get_name() {
    return 'full_name';
  }
}

class Decoratable_Test_Object implements Decoratable {
  public $first_name = 'first_name';

  public function decorate() {
    return new Decorator_Test_Object( $this );
  }
}
