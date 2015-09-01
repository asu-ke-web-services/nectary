<?php

namespace Nectary\Tests\Integration;

use Nectary\Facades\Twitter_Json_Facade;

/**
 * Test the Twitter JSON Facade in the framework
 * This will ACTUALLY ping Twitter
 *
 * @group facade
 * @group integration
 */
class Twitter_Json_Facade_Test extends \PHPUnit_Framework_TestCase {
  /**
   * Random Twitter API keys pulled from the Internet
   */
  const CONSUMER_KEY              = 'VXD22AD9kcNyNgsfW6cwkWRkw';
  const CONSUMER_SECRET           = 'y0k3z9Y46V0DMAKGe4Az2aDtqNt9aXjg3ssCMCldUheGBT0YL9';
  const OAUTH_ACCESS_TOKEN        = '3232926711-kvMvNK5mFJlUFzCdtw3ryuwZfhIbLJtPX9e8E3Y';
  const OAUTH_ACCESS_TOKEN_SECRET = 'EYrFp0lfNajBslYV3WgAGmpHqYZvvNxP5uxxSq8Dbs1wa';

  function test_returns_tweets() {
    $facade = new Twitter_Json_Facade();

    $json_feed = $facade->get_feed(
        array(
          'query' => '@asugreen',
          'limit' => '15',
          'oauth_access_token'        => self::OAUTH_ACCESS_TOKEN,
          'oauth_access_token_secret' => self::OAUTH_ACCESS_TOKEN_SECRET,
          'consumer_key'              => self::CONSUMER_KEY,
          'consumer_secret'           => self::CONSUMER_SECRET,
        )
    );

    $json_feed->retrieve_items();

    $items = $json_feed->get_items();
    $this->assertCount( 15, $items['statuses'] );
  }
}
