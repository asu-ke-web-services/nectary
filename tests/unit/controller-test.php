<?php

namespace Nectary\Tests;

use Nectary\Controller;

/**
 * Test the controller class in the framework
 *
 * @group controller
 */
class Controller_Test extends \PHPUnit_Framework_TestCase {
  function test_exists() {
    $this->assertEquals( 'Nectary\Controller', Controller::class );
  }
}
