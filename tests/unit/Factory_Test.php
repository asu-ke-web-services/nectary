<?php

namespace Nectary\Tests;

use Nectary\Factories\Factory;
use PHPUnit\Framework\TestCase;

/**
 * Test the factory class in the framework
 *
 * @group factory
 */
class Factory_Test extends TestCase {
  function test_exists() {
    $this->assertEquals( 'Nectary\Factory', Factory::class );
  }
}
