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

  function test_simple_route() {
    // TODO
  }

  function test_named_arguments_are_mapped() {
    // TODO
  }

  /**
   * Special case of test_named_arguments_are_mapped where
   * one of the named arguments is itself an array.
   */
  function test_route_with_array_parameter() {
    // Test function( $a, $b, $options ){}
    // $a String
    // $b String
    // $options array

    // TODO
  }

  function test_builds_dependencies() {
    // TODO
  }

  function test_route_can_fail_with_invalid_request() {
    // TODO
  }
}
