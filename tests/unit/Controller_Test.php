<?php

namespace Nectary\Tests;

use Nectary\Controllers\Controller;
use PHPUnit\Framework\TestCase;

/**
 * Test the controller class in the framework
 *
 * @group controller
 */
class Controller_Test extends TestCase {
  function test_exists() {
    $this->assertEquals( 'Nectary\Controller', Controller::class );
  }

  // No other tests to run
}
