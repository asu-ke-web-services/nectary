<?php

namespace Nectary\Tests;

use Nectary\Singleton;
use PHPUnit\Framework\TestCase;

/**
 * Test the singleton class in the framework
 *
 * @group singleton
 */
class Singleton_Test extends TestCase {
  function test_exists() {
    $this->assertEquals( 'Nectary\Singleton', Singleton::class );
  }

  function test_cannot_be_constructed() {
    // TODO
  }

  function test_there_can_only_be_one() {
    // TODO
  }

  function test_not_clonable() {
    // TODO
  }
}
