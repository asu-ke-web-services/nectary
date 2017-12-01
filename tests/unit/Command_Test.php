<?php

namespace Nectary\Tests;

use Nectary\Commands\Command;
use PHPUnit\Framework\TestCase;

/**
 * Test the command class in the framework
 *
 * @group command
 */
class Command_Test extends TestCase {
  function test_dispatchable() {
    $command = $this->getMockBuilder( Command::class )
    ->setMethods( [ 'handle' ] )
    ->getMockForAbstractClass();

    $command->expects( $this->once() )
    ->method( 'handle' );

    \dispatch( $command );
  }
}
