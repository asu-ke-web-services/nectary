<?php

namespace Nectary\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @group utility
 */
class Dao_Utilities_Test extends TestCase {
	public function test_returns_given_string_when_valid() {
		$this->assertEquals( 'citations.date DESC', sanitize_order_by( 'citations.date DESC' ) );
		$this->assertEquals( 'blah.id ASC, table2.id DESC', sanitize_order_by( 'blah.id ASC, table2.id DESC' ) );
	}

	public function test_returns_empty_string_when_given_invalid_string() {
		$this->assertEquals( '', sanitize_order_by( ';' ) );
		$this->assertEquals( '', sanitize_order_by( "' OR '1'='1" ) );
	}
}
