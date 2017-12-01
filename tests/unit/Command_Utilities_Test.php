<?php

namespace Nectary\Tests;

use Nectary\Commands\Command;
use PHPUnit\Framework\TestCase;

/**
 * Test the command utilities
 */
class Command_Utilities_Test extends TestCase {
  function test_dispatch_calls_handle() {
    $command = $this->getMockBuilder( Command::class )
    ->setMethods( [ 'handle' ] )
    ->getMockForAbstractClass();

    $command->expects( $this->once() )
    ->method( 'handle' );

    \dispatch( $command );
  }

  function test_dispatch_returns_results() {
    $command = $this->getMockBuilder( Command::class )
    ->setMethods( [ 'handle' ] )
    ->getMockForAbstractClass();

    $command->expects( $this->any() )
    ->method( 'handle' )
    ->will( $this->returnValue( 'value' ) );

    $this->assertEquals( 'value', \dispatch( $command ) );
  }
}
