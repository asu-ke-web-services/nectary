<?php

namespace Nectary\Tests;

use Nectary\Factory;

/**
 * Test the factory class in the framework
 *
 * @group factory
 */
class Factory_Test extends \PHPUnit_Framework_TestCase {
  function test_exists() {
    $this->assertEquals( 'Nectary\Factory', Factory::class );
  }
}
