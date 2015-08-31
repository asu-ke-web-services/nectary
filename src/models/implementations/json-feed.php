<?php

namespace Nectary\Models;

use Nectary\Feed;

class Json_Feed implements Feed {
  private $url;
  private $items;
  private $feed_callback;

  public function __construct( $url, $feed_callback = null ) {
    $this->url = $url;
    $this->feed_callback = $feed_callback;
  }

  /**
   * @param $look_at String|Boolean use to grab items from a section of the json
   */
  public function retrieve_items( $look_at = false ) {
    $json = call_user_func( $this->feed_callback, $this->url );
    $raw  = $this->parse_feed( $json );

    if ( $look_at !== false ) {
      $this->items = $raw[ $look_at ];
    } else {
      $this->items = $raw;
    }
  }

  public function sort_by_date( $order = 'asc' ) {
    // TODO
  }

  public function get_items() {
    return $this->items;
  }

  public function set_items( $items ) {
    $this->items = $items;
  }

  private function parse_feed( $json ) {
    $json = json_decode( $json, true );
    if ( empty( $json ) ) {
      error_log( 'Json was empty' );
      throw new \Exception( 'Feed could not be loaded' );
    }

    if ( $error = $this->get_error( $json ) ) {
      error_log( 'Json Errored with: ' . $error );
    }

    return $json;
  }

  private function get_error( $json ) {
    $has_error = array_key_exists( 'errors' , $json )
              && array_key_exists( 0 , $json['errors'] )
              && array_key_exists( 'message' , $json['errors'][0] );

    if ( $has_error ) {
      return $json['errors'][0]['message'];
    }
  }
}
