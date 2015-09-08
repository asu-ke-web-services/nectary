<?php

namespace Nectary\Services;

use Nectary\Models\Json_Feed;
use Nectary\Services\Feed_Service;

class Json_Feed_Service extends Feed_Service {
  /**
   * @return Json_Feed
   */
  public function get_feed( $url ) {
    return new Json_Feed( $url, array( $this, 'get_curl_feed_data' ) );
  }

  public function get_curl_feed_data( $url ) {
    $session = curl_init( $url );

    curl_setopt( $session, CURLOPT_RETURNTRANSFER, true );

    $json = curl_exec( $session );

    if ( curl_error( $session ) ) {
      $error_message = 'JSON Call failed ';

      if ( function_exists( 'curl_strerror' ) ) {
        $error_message .= curl_strerror( curl_errno( $session ) );
      } else {
        $error_message .= curl_errno( $session );
      }

      throw new \Exception( $error_message );
    }
    curl_close( $session );

    return $json;
  }
}