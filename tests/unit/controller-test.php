<?php

namespace Nectary\Tests;

use Nectary\Controller;
use PHPUnit\Framework\TestCase;

/**
 * Test the controller class in the framework
 *
 * @group controller
 */
class Controller_Test extends TestCase {
	public function test_exists() {
		$this->assertEquals( 'Nectary\Controller', Controller::class );
	}

	// No other tests to run
}
