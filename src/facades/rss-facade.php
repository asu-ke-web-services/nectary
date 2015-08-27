<?php

namespace Nectary\Facades;

use Nectary\Models\Rss_Feed;

abstract class Rss_Facade {
  private $feed_callback;

  public function __construct() {
    $this->feed_callback = $this->load_dependencies();
  }

  public function get_feed( $url ) {
    return new Rss_Feed( $url, $this->feed_callback );
  }

  public function merge_feeds( $feeds, $options ) {
    $merged_feed = [];

    foreach ( $feeds as $feed ) {
      $items = $feed->get_items();
      $merged_feed = $merged_feed + $items;
    }

    if ( array_key_exists( 'unique', $options ) &&
         $options['unique'] ) {
      $merged_feed = $this->unique_feed( $merged_feed, $options );
    }

    $new_feed = new Rss_Feed( '', $this->feed_callback );


    $new_feed->set_items( $merged_feed );

    return $new_feed;
  }

  /**
   * @param $feed Array<SimplePie>
   */
  public function unique_feed( $feed, $options ) {
    $unique = [];

    $look_at = 'get_title';

    if ( array_key_exists( 'look_at', $options ) ) {
      $look_at = 'get_' . $options['look_at'];
    }

    for ( $i = 1; $i < count( $feed ); $i++ ) {
      $duplicate = false;
      for ( $j = 0; $j < count( $feed ); $j++ ) {
        if ( $i !== $j ) {
          if ( $feed[ $i ]->$look_at() === $feed[ $j ]->$look_at() ) {
            // Duplicate found
            $duplicate = true;
            break;
          }
        }
      }

      if ( ! $duplicate ) {
        $unique[] = $feed[ $i ];
      }
    }

    return $unique;
  }

  public function to_array( $simple_pie ) {
    $arrayified = [];

    if ( is_array( $simple_pie ) ) {
      foreach( $simple_pie as $slice ) {
        $arrayified[] = $this->to_array( $slice );
      }
    } else if ( $simple_pie instanceof \SimplePie_Item ) {
      $arrayified = array(
          'permalink' => $this->get( 'permalink', $simple_pie ),
          'title' => $this->get( 'title', $simple_pie ),
          'readable_date' => $this->get( 'date', $simple_pie, [ 'j F Y @ g:i a' ] ),
          'description' => $this->get( 'description', $simple_pie ),
      );
    }

    return $arrayified;
  }

  private function get( $key, $pie, $options = null ) {
    $method = 'get_' . $key;
    if ( method_exists( $pie, $method ) ) {
      if ( ! is_null( $options ) ) {
        return call_user_func_array (
            array( $pie, $method ),
            $options
        );
      }

      return $pie->$method();
    } else {
      return '';
    }
  }

  /**
   * @return Array|String Function to call to get an rss feed
   */
  abstract function load_dependencies();
}
