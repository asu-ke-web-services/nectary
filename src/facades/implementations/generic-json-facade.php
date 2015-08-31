<?php

namespace Nectary\Facades;

use Nectary\Models\Json_Feed;
use Nectary\Facades\Rss_Facade;

class Generic_Json_Facade extends Rss_Facade {
  public function load_dependencies() {
    return array( $this, 'get_feed' );
  }

  public function get_feed( $url ) {
    return new Json_Feed( $url, array( $this, 'get_curl_feed_data' ) );
  }

  public function get_curl_feed_data( $url ) {
    $session = curl_init( $url );

    curl_setopt( $session, CURLOPT_RETURNTRANSFER, true );

    $json = curl_exec( $session );

    if ( curl_error( $session ) ) {
      throw new Exception( 'JSON Call failed ' . curl_strerror( curl_errno( $session ) ) );
    }
    curl_close( $session );

    return $json;
  }
}
