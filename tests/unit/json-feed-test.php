<?php

namespace Nectary\Tests;

use Nectary\Models\Json_Feed;

/**
 * @group model
 */
class Json_Feed_Test extends \PHPUnit_Framework_TestCase {
  function test_json_feed_parses_json() {
    $obj = $this->getMockBuilder( 'Object' )
    ->setMethods( ['get_curl_feed_data' ] )
    ->getMock();

    $obj->expects( $this->once() )
    ->method( 'get_curl_feed_data' )
    ->will( $this->returnValue( '{ "key" : "value" } ') );

    $url = 'http://mock.it';

    $json_feed = new Json_Feed( $url, array( $obj, 'get_curl_feed_data' ) );

    $json_feed->retrieve_items();

    $items = $json_feed->get_items();

    $this->assertEquals( [ 'key' => 'value' ], $items );
  }

  function test_json_feed_can_sort_by_date() {
    // TODO
  }

  function test_json_feed_can_set_items() {
    // TODO
  }

  function test_json_feed_can_get_unique_items() {
    // TODO
  }
}
