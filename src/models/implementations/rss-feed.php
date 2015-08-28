<?php

namespace Nectary\Models;

class Rss_Feed {
  private $url;
  private $items;
  private $feed_callback;

  public function __construct( $url = '', $feed_callback = null ) {
    $this->url = $url;
    $this->feed_callback = $feed_callback;
  }

  public function retrieve_items() {
    $feed = call_user_func( $this->feed_callback, $this->url );

    if ( is_wp_error( $feed ) ) {
      throw new \Exception( 'Feed could not be loaded' );
    } else {
      $this->items = $feed->get_items( 0 );
    }
  }

  public function sort_by_date( $order = 'asc' ) {
    usort( $this->items, function( $a, $b ) use ( $order ) {
      $a_start_date = strtotime( $a->get_date() );
      $b_start_date = strtotime( $b->get_date() );
      if ( $a_start_date == $b_start_date ) {
        return 0;
      }

      if ( $order === 'asc' ) {
        return ( $a_start_date > $b_start_date ) ? 1 : -1;
      } else {
        return ( $a_start_date < $b_start_date ) ? 1 : -1;  
      }
    } );
  }

  public function get_items() {
    return $this->items;
  }

  public function set_items( $items ) {
    $this->items = $items;
  }
}
