<?php

namespace Nectary\Tests;

use Nectary\Routers\Router;
use PHPUnit\Framework\TestCase;

/**
 * Test the router class in the framework
 *
 * @group router
 */
class Router_Test extends TestCase {
  function test_exists() {
    $this->assertEquals( 'Nectary\Router', Router::class );
  }

  function test_routes_data_to_function() {
    $router = $this->getMockBuilder( 'Nectary\Router' )
    ->getMockForAbstractClass();

    $mock = $this->getMockBuilder( 'Object' )
    ->setMethods( [ 'get_view' ] )
    ->getMock();

    $mock->expects( $this->once() )
    ->method( 'get_view' )
    ->with( $this->equalTo( 1 ), $this->equalTo( 2 ), $this->equalTo( 3 ) );

    $router->routes = array(
      'get_view' => array(
        'to'       => array( $mock, 'get_view' ),
        'expects'  => [ 'value1', 'value2', 'value3' ]
      ),
    );

    $router->get_view( 1, 2, 3 );
  }

  function test_invalid_route_returns_empty() {
    $router = $this->getMockBuilder( 'Nectary\Router' )
    ->getMockForAbstractClass();

    $mock = $this->getMockBuilder( 'Object' )
    ->setMethods( [ 'get_view' ] )
    ->getMock();

    $mock->expects( $this->exactly( 0 ) )
    ->method( 'get_view' );

    $router->routes = array(
      'get_view' => array(
        'to'       => array( $mock, 'get_view' ),
        'expects'  => [ 'value1', 'value2', 'value3' ]
      ),
    );

    $value = $router->get_view_wrong( 1, 2, 3 );

    $this->assertEmpty( $value );
  }

  function test_routes_not_set_returns_empty() {
    $router = $this->getMockBuilder( 'Nectary\Router' )
    ->getMockForAbstractClass();

    $mock = $this->getMockBuilder( 'Object' )
    ->setMethods( [ 'get_view' ] )
    ->getMock();

    $mock->expects( $this->exactly( 0 ) )
    ->method( 'get_view' );

    $value = $router->get_view_wrong( 1, 2, 3 );

    $this->assertEmpty( $value );
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
