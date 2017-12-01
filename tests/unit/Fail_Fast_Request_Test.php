<?php

namespace Nectary\Tests;

use Nectary\Requests\Fail_Fast_Request;
use PHPUnit\Framework\TestCase;

/**
 * @group request
 */
class Fail_Fast_Request_Test extends TestCase {
  function test_error_callback_is_not_called_when_validation_passes() {
    $request = $this->getMockForAbstractClass( Fail_Fast_Request::class );
    $request->expects( $this->once() )
    ->method( 'validation_rules' )
    ->will( $this->returnValue( [ 'test' =>  true ] ) );


    $mock_function = $this->getMockBuilder( 'Object' )
    ->setMethods( ['no_call_function'] )
    ->getMock();

    $mock_function->expects( $this->exactly( 0 ) )
    ->method( 'no_call_function' )
    ->will($this->returnValue( false ) );

    $request->validate( array( $mock_function, 'no_call_function' ) );
  }

  function test_error_callback_is_called_with_failures() {
    $request = $this->getMockForAbstractClass( Fail_Fast_Request::class );
    $request->expects( $this->once() )
    ->method( 'validation_rules' )
    ->will( $this->returnValue( [ 'test' =>  'my error message' ] ) );


    $mock_function = $this->getMockBuilder( 'Object' )
    ->setMethods( ['error_function'] )
    ->getMock();

    $mock_function->expects( $this->exactly( 1 ) )
    ->method( 'error_function' )
    ->will($this->returnValue( 'my failure object' ) );

    $result = $request->validate( array( $mock_function, 'error_function' ) );

    $this->assertEquals( 'my failure object', $result );
  }

  function test_error_callback_is_called_with_many_failures() {
    $request = $this->getMockForAbstractClass( Fail_Fast_Request::class );
    $request->expects( $this->once() )
    ->method( 'validation_rules' )
    ->will( $this->returnValue(
        array(
          'test' =>  'my error message',
          'test2' =>  'another error message',
        )
      )
    );


    $mock_function = $this->getMockBuilder( 'Object' )
    ->setMethods( ['error_function'] )
    ->getMock();

    $mock_function->expects( $this->exactly( 1 ) )
    ->method( 'error_function' )
    ->will(
        $this->onConsecutiveCalls(
            'my failure object',
            'another failure object'
        )
    );

    $result = $request->validate( array( $mock_function, 'error_function' ) );

    $this->assertEquals( 'my failure object', $result );
  }
}
