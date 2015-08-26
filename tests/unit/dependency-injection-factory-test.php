<?php

namespace Nectary\Tests;

use Nectary\Factories\Dependency_Injection_Factory;
use Nectary\Tests\Mocks\Dif_Test_Object;
use Nectary\Tests\Mocks\Dif_Nested_Test_Object;

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
