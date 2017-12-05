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
  public function test_exists() {
    $this->assertEquals( 'Nectary\Singleton', Singleton::class );
  }

  public function test_cannot_be_constructed() {
    // TODO
  }

  public function test_there_can_only_be_one() {
    // TODO
  }

  public function test_not_clonable() {
    // TODO
  }
}
