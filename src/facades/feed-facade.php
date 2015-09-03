<?php

namespace Nectary\Facades;

use Nectary\Feed;
use Nectary\Services\Feed_Service;

class Feed_Facade {
  private $service;

  public function __construct( Feed_Service $service ) {
    $this->service = $service;
  }

  public function get_feed( $url ) {
    return $this->service->get_feed( $url );
  }

  /**
   * @param $feeds array<Feed>
   */
  public function merge_feeds( array $feeds ) {
    $merged_feed = [];

    foreach ( $feeds as $feed ) {
      $items = $feed->get_items();
      $merged_feed = array_merge( $merged_feed, $items );
    }

    $new_feed = $this->service->get_feed( '' );

    $new_feed->set_items( $merged_feed );

    return $new_feed;
  }

  public function unique_feed( Feed $feed ) {
    $new_feed = $this->service->get_feed( '' );

    $new_feed->set_items( $feed->get_unique_items() );

    return $new_feed;
  }
}
