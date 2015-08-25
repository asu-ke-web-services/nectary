<?php

namespace Nectary\Tests;

use Nectary\Decoratable;

/**
 * Test the decoratable interface in the framework
 *
 * @group decorator
 */
class Decoratable_Test extends \PHPUnit_Framework_TestCase {
  function test_exists() {
    $this->assertEquals( 'Nectary\Decoratable', Decoratable::class );
  }
}
