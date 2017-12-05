<?php

namespace Nectary\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Test the ensure default method
 */
class Ensure_Default_Test extends TestCase {
	public function test_ensure_default_provides_default() {
		$data = array();

		\ensure_default( $data, 'key', 'value' );

		$this->assertEquals( 'value', $data['key'] );
	}

	public function test_ensure_default_provides_value() {
		$data = array( 'key' => '???');

		\ensure_default( $data, 'key', 'value' );

		$this->assertEquals( '???', $data['key'] );
	}
}
