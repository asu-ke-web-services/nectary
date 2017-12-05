<?php

namespace Nectary\Tests;

use Nectary\Facades\Feed_Facade;
use Nectary\Models\Json_Feed;
use PHPUnit\Framework\TestCase;

/**
 * @group facade
 * @group feed
 */
class Feed_Facade_Test extends TestCase {
  private $facade_mock;

  protected function setUp() {
    $this->url = 'http://test.url';
    $this->feed = new Json_Feed( '' );

    $this->facade_mock = $this->getMockBuilder( 'Nectary\Services\Feed_Service' )
    ->setMethods( [ 'get_feed', 'get_items', 'set_items' ] )
    ->getMockForAbstractClass();
  }

  public function test_get_feed_gets_feed_from_feed_service() {
    $this->facade_mock->expects( $this->once() )
    ->method( 'get_feed' )
    ->with( $this->url )
    ->will( $this->returnValue( $this->feed ) );

    $feed_facade = new Feed_Facade( $this->facade_mock );

    $this->assertEquals( $this->feed, $feed_facade->get_feed( $this->url ) );
  }

  public function test_merge_feeds_merges_feeds() {
    $this->facade_mock->expects( $this->once() )
    ->method( 'get_feed' )
    ->will( $this->returnValue( new Json_Feed( '' ) ) );

    $feed_facade = new Feed_Facade( $this->facade_mock );

    $feed_1 = new Json_Feed( '' );
    $feed_1->set_items( array( 1, 1, 1 ) );

    $feed_2 = new Json_Feed( '' );
    $feed_2->set_items( array( 1, 1, 1 ) );

    $feeds = array( $feed_1, $feed_2 );

    $new_feed = $feed_facade->merge_feeds( $feeds );

    $this->assertCount( 6, $new_feed->get_items() );
  }

  public function test_unique_feed_returns_a_unique_feed() {
    $this->facade_mock->expects( $this->once() )
    ->method( 'get_feed' )
    ->will( $this->returnValue( new Json_Feed( '' ) ) );

    $feed_facade = new Feed_Facade( $this->facade_mock );

    $feed = new Json_Feed( '' );
    $feed->set_items( array( 1, 1, 1 ) );

    $unique_feed = $feed_facade->unique_feed( $feed );

    $this->assertCount( 1, $unique_feed->get_items() );
    $this->assertEquals( [1], $unique_feed->get_items() );
  }
}
