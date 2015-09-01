<?php

namespace Nectary\Tests;

/**
 * @group utility
 */
class String_Utilities_Test extends \PHPUnit_Framework_TestCase {
  function test_starts_with_returns_true() {
    $needle = '@';
    $haystack = '@asugreen';

    $this->assertTrue( starts_with( $haystack, $needle ) );
  }

  function test_starts_with_returns_false() {
    $needle = '@';
    $haystack = '.@asugreen';

    $this->assertFalse( starts_with( $haystack, $needle ) );
  }

}
