<?php

namespace Nectary\Tests\Integration;

use Nectary\Services\Json_Feed_Service;
use PHPUnit\Framework\TestCase;

/**
 * Test the Generic JSON Service in the framework
 *
 * @group service
 * @group integration
 */
class Json_Feed_Service_Test extends TestCase {
	protected function setUp() {
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

	public function test_returns_json_feed() {
		$service = new Json_Feed_Service();

		$json_feed = $service->get_feed( $this->yahoo_weather );

		$this->assertInstanceOf( 'Nectary\Models\Json_Feed', $json_feed );
	}

	public function test_returns_json_data() {
		$service = new Json_Feed_Service();

		$json_feed = $service->get_feed( $this->yahoo_weather );

		$json_feed->retrieve_items();
		$items = $json_feed->get_items();

		$this->assertCount( 1, $items );
		$this->assertTrue( array_key_exists( 'results', $items['query'] ) );
	}

	/**
	 * @expectedException \Exception
	 */
	public function test_throws_error_when_invalid() {

		$service = new Json_Feed_Service();
		$json_feed = $service->get_feed( $this->bad_url );

		$json_feed->retrieve_items();
	}
}
