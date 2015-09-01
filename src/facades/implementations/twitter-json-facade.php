<?php

namespace Nectary\Facades;

use Nectary\Models\Json_Feed;
use Nectary\Facades\Rss_Facade;

class Twitter_Json_Facade extends Rss_Facade {
  private $options;

  public function load_dependencies() {
    return array( $this, 'get_feed' );
  }

  public function get_feed( $options ) {
    ensure_default( $options, 'query', '@asugreen' );
    ensure_default( $options, 'limit', 20 );
    ensure_default( $options, 'oauth_access_token', '' );
    ensure_default( $options, 'oauth_access_token_secret', '' );
    ensure_default( $options, 'consumer_key', '' );
    ensure_default( $options, 'consumer_secret', '' );

    $this->options = $options;

    return new Json_Feed( 'https://api.twitter.com/1.1/', array( $this, 'get_curl_feed_data' ) );
  }

  public function get_curl_feed_data() {
    $query_options = $this->create_query_options( $this->options );

    $curl_options             = array(
      CURLOPT_HTTPHEADER      => $query_options['http_header'],
      CURLOPT_HEADER          => false,
      CURLOPT_URL             => $query_options['url'],
      CURLOPT_RETURNTRANSFER  => true,
      CURLOPT_SSL_VERIFYPEER  => false,
    );

    $feed = curl_init();

    curl_setopt_array( $feed, $curl_options );

    $json = curl_exec( $feed );

    if ( curl_error( $feed ) ) {
      $json   = '';
      error_log( 'Twitter Facade could not curl! ' . curl_strerror( curl_errno( $feed ) ) );
    }

    curl_close( $feed );

    return $json;
  }

  private function create_query_options( $options ) {
    $query   = $options['query'];
    $limit   = $options['limit'];
    $api_url = 'https://api.twitter.com/1.1/search/tweets.json';

    if ( starts_with( $query, '@' ) ) {
      $api_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    }

    $oauth = $this->create_oauth( $api_url, $query, $options );

    $query_options = array(
      'http_header' => $this->create_query_http_header( $oauth ),
      'url'         => $this->create_query_url( $api_url, $query, $limit ),
    );

    return $query_options;
  }

  private function create_oauth( $api_url, $query, $options ) {
    $oauth = array(
      'oauth_consumer_key'     => $options['consumer_key'],
      'oauth_nonce'            => uniqid(),
      'oauth_signature_method' => 'HMAC-SHA1',
      'oauth_token'            => $options['oauth_access_token'],
      'oauth_timestamp'        => time(),
      'oauth_version'          => '1.0',
      'q'                      => $query,
      'count'                  => $options['limit'],
      'include_rts'            => 1,
    );

    $oauth['oauth_signature'] = $this->create_oauth_signature( $oauth, $api_url, $options );

    return $oauth;
  }

  private function create_oauth_signature( $oauth, $api_url, $options ) {
    $base_url  = 'GET';
    $base_url .= '&';
    $base_url .= rawurlencode( $api_url );
    $base_url .= '&';
    $base_url .= rawurlencode( implode( '&', ksort( $oauth ) ) );

    $composite_key  = rawurlencode( $options['consumer_secret'] );
    $composite_key .= '&';
    $composite_key .= rawurlencode( $options['oauth_access_token_secret'] );

    $oauth_signature = base64_encode(
        hash_hmac(
            'sha1',
            $base_url,
            $composite_key,
            true 
        )
    );

    return $oauth_signature;
  }

  private function create_query_http_header( $oauth ) {
    $request_header  = 'Authorization: OAuth ';
    $request_header .= implode( ', ', $this->get_encoded_values( $oauth ) );

    return array(
      $request_header,
      'Content-Type: application/json',
      'Expect:',
    );
  }

  private function get_encoded_values( $oauth ) {
    foreach ( $oauth as $key => $value ) {
      yield "$key=\"" . rawurlencode( $value ) . '"';
    }
  }

  private function create_query_url( $api_url, $query, $limit ) {
    $query  = $api_url;
    $query .= '?q=';
    $query .= rawurlencode( $query );
    $query .= '&count=';
    $query .= $limit;
    $query .= '&include_rts=1';

    return $query;
  }
}
