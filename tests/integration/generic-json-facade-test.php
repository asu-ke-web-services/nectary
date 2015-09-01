<?php

namespace Nectary\Tests\Integration;

use Nectary\Facades\Generic_Json_Facade;

/**
 * Test the Generic JSON Facade in the framework
 *
 * @group facade
 * @group integration
 */
class Generic_Json_Facade_Test extends \PHPUnit_Framework_TestCase {
  function setUp() {
    $this->yahoo_weather = implode( '', [
      'http://query.yahooapis.com/v1/public/yql',
      '?q=select+%2A+from+weather.forecast+where+woeid+in+%28',
      'select+woeid+from+geo.places%281%29+where+text%3D%22tempe%2C+az',
      '%22%29&format=json'
    ]);

    $this->bad_url = implode( '', [
      'invalid://yahooapis.com/v0/public/yql',
      '?q=select+%2A+from+weather.forecast+where+woeid+in+%28',
      'select+woeid+from+geo.places%281%29+where+text%3D%22tempe%2C+az',
      '%22%29&format=json'
    ]);
  }

  function test_returns_json_feed() {
    $facade = new Generic_Json_Facade();

    $json_feed = $facade->get_feed( $this->yahoo_weather );

    $this->assertInstanceOf( 'Nectary\Models\Json_Feed', $json_feed );
  }

  function test_returns_json_data() {
    $facade = new Generic_Json_Facade();

    $json_feed = $facade->get_feed( $this->yahoo_weather );

    $json_feed->retrieve_items();
    $items = $json_feed->get_items();

    $this->assertCount( 1, $items );
    $this->assertTrue( array_key_exists( 'results', $items['query'] ) );
  }

   /**
   * @expectedException \Exception
   */
  function test_throws_error_when_invalid() {

    $facade = new Generic_Json_Facade();
    $json_feed = $facade->get_feed( $this->bad_url );

    $json_feed->retrieve_items();
  }
}
