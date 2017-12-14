<?php

namespace Nectary\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Test the command utilities
 */
class Command_Utilities_Test extends TestCase {
	public function test_dispatch_calls_handle() {
		$command = $this->getMockBuilder( 'Nectary\Command' )
		->setMethods( [ 'handle' ] )
		->getMockForAbstractClass();

		$command->expects( $this->once() )
		->method( 'handle' );

		\dispatch( $command );
	}

	public function test_dispatch_returns_results() {
		$command = $this->getMockBuilder( 'Nectary\Command' )
		->setMethods( [ 'handle' ] )
		->getMockForAbstractClass();

		$command->expects( $this->any() )
		->method( 'handle' )
		->will( $this->returnValue( 'value' ) );

		$this->assertEquals( 'value', \dispatch( $command ) );
	}
}
