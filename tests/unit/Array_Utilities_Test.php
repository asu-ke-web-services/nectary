<?php

namespace Nectary\Tests;

/**
 * @group utility
 */
class Array_Utilities_Test extends \PHPUnit_Framework_TestCase {
  function test_array_peak() {
    $this->assertEquals( array_peak( [ 'first', 'second' ] ), 'first' );
    $this->assertEquals( array_peak( [] ), null );
    $this->assertEquals( array_peak( null ), null );
  }

  function testTo_array() {
    $this->assertEquals( array(), to_array( null ) );
    $this->assertEquals( array( 'blah' ), to_array( 'blah' ) );
    $this->assertEquals( array( 'blah' ), to_array( array( 'blah' ) ) );
  }
}
