<?php

namespace Nectary\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @group utility
 */
class Array_Utilities_Test extends TestCase {
	public function test_array_peek() {
		$this->assertEquals( array_peek( [ 'first', 'second' ] ), 'first' );
		$this->assertEquals( array_peek( [] ), null );
		$this->assertEquals( array_peek( null ), null );
	}

	public function test_to_array() {
		$this->assertEquals( array(), to_array( null ) );
		$this->assertEquals( array( 'blah' ), to_array( 'blah' ) );
		$this->assertEquals( array( 'blah' ), to_array( array( 'blah' ) ) );
	}
}
