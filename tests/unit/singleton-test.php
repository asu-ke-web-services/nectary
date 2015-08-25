<?php

namespace Nectary\Tests;

use Nectary\Singleton;

/**
 * Test the singleton class in the framework
 *
 * @group singleton
 */
class Singleton_Test extends \PHPUnit_Framework_TestCase {
  function test_exists() {
    $this->assertEquals( 'Nectary\Singleton', Singleton::class );
  }
}
