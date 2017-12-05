<?php

namespace Nectary\Tests;

use Nectary\Factory;
use PHPUnit\Framework\TestCase;

/**
 * Test the factory class in the framework
 *
 * @group factory
 */
class Factory_Test extends TestCase {
  public function test_exists() {
    $this->assertEquals( 'Nectary\Factory', Factory::class );
  }
}
