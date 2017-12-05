<?php

namespace Nectary\Services;

use Nectary\Models\Json_Feed;
use Nectary\Services\Feed_Service;

/**
 * Feed Service for Twitter. Defaults
 * to using curl.
 *
 * @extends Feed_Service
 */
class Twitter_Feed_Service extends Feed_Service {
	private $options;

	public function get_feed( $options ) {
		ensure_default( $options, 'query_type', 'search' );
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

		switch ( $this->options['query_type'] ) {
			case 'search':
				return $json;
			break;
			case 'screenname':
				return json_encode( array( 'statuses' => json_decode( $json ) ) );
			break;
		}

		return $json;
	}

	private function create_query_options( $options ) {
		$query = $options['query'];
		$type  = $options['query_type'];
		$limit = $options['limit'];
		$api_url = '';
		$query_type = '';
		switch ( $type ) {
			case 'search':
				$api_url    = 'https://api.twitter.com/1.1/search/tweets.json';
				$query_type = 'q';
				break;
			case 'screenname':
				$api_url    = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
				$query_type = 'screen_name';
				break;
		}

		$oauth = $this->create_oauth( $api_url, $query, $query_type, $options );

		$query_options = array(
			'http_header' => $this->create_query_http_header( $oauth ),
			'url'         => $this->create_query_url( $api_url, $query, $query_type, $limit ),
		);

		return $query_options;
	}

	private function create_oauth( $api_url, $query, $query_type, $options ) {
		$oauth = array(
			'oauth_consumer_key'     => $options['consumer_key'],
			'oauth_nonce'            => uniqid(),
			'oauth_signature_method' => 'HMAC-SHA1',
			'oauth_token'            => $options['oauth_access_token'],
			'oauth_timestamp'        => time(),
			'oauth_version'          => '1.0',
			'count'                  => $options['limit'],
			'include_rts'            => 1,
		);

		$oauth[ $query_type ] = $query;

		$oauth['oauth_signature'] = $this->create_oauth_signature( $oauth, $api_url, $options );

		return $oauth;
	}

	private function create_oauth_signature( $oauth, $api_url, $options ) {
		ksort( $oauth );

		$base_url  = 'GET';
		$base_url .= '&';
		$base_url .= rawurlencode( $api_url );
		$base_url .= '&';
		$base_url .= rawurlencode( implode( '&', $this->get_encoded_values( $oauth ) ) );

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
		$encode_params = [];

		foreach ( $oauth as $key => $value ) {
			$encode_params[]  = "$key=" . rawurlencode( $value );
		}

		return $encode_params;
	}

	private function create_query_url( $api_url, $query, $query_type, $limit ) {
		$query_url  = $api_url;
		$query_url .= '?' . $query_type . '=';
		$query_url .= rawurlencode( $query );
		$query_url .= '&count=';
		$query_url .= $limit;
		$query_url .= '&include_rts=1';
		return $query_url;
	}
}
