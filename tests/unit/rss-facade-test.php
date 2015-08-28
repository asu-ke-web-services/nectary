<?php

namespace Nectary\Tests;

use Nectary\Tests\Mocks\Rss_Facade_Test_Object;

/**
 * Test the RSS Facade
 *
 * @group facade
 */
class Rss_Facade_Test extends \PHPUnit_Framework_TestCase {
  function test_unique_returns_a_single_element() {
    $facade = new Rss_Facade_Test_Object();
    $feed   = $this->getMockBuilder( 'Object' )
                   ->setMethods( ['get_title'] )
                   ->getMock();
    $feed->expects( $this->any() )
         ->method( 'get_title' )
         ->will( $this->returnValue( 'test' ) );

    $uniqued = $facade->unique_feed(
        array(
          $feed,
          $feed,
          $feed,
          $feed,
          $feed,
        )
    );

    $this->assertEquals( 1, count( $uniqued ) );
    $this->assertEquals( 'test', $uniqued[0]->get_title() );
  }
}
