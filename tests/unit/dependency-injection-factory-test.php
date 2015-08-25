<?php

namespace Nectary\Tests;

use Nectary\Factories\Dependency_Injection_Factory;

/**
 * Test the dependency injection factory class in the framework
 *
 * @group dependency-injection-factory
 */
class Dependency_Injection_Factory_Test extends \PHPUnit_Framework_TestCase {
  function test_exists() {
    $this->assertEquals( 'Nectary\Factories\Dependency_Injection_Factory', Dependency_Injection_Factory::class );
  }

  function test_can_construct_class() {
    $factory = new Dependency_Injection_Factory(
        Dif_Test_Object::class,
        '__construct',
        array()
    );

    list( $instance, $_, $__ ) = $factory->build();
    $speak = $instance->exclaim();

    $this->assertInstanceOf( Dif_Test_Object::class, $instance );
    $this->assertEquals( 'wow', $speak );
  }

  function test_can_construct_nested_dependencies() {
    $factory = new Dependency_Injection_Factory(
        Dif_Nested_Test_Object::class,
        '__construct',
        array()
    );

    list( $instance, $_, $__ ) = $factory->build();
    $speak = $instance->exclaim();

    $this->assertInstanceOf( Dif_Nested_Test_Object::class, $instance );
    $this->assertEquals( 'wow!', $speak );
  }

  function test_can_pass_variables_to_class() {
    // TODO
  }

  function test_can_construct_singleton() {
    // TODO
  }

  function test_will_return_validators_for_requests() {
    // TODO
  }
}

class Dif_Test_Object {
  public function __construct() {}
  public function exclaim() { return 'wow'; }
}

class Dif_Nested_Test_Object {
  public function __construct( Dif_Test_Object $d ) {
    $this->d = $d;
  }
  public function exclaim() { return $this->d->exclaim() . '!'; }
}