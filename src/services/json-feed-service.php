<?php

namespace Nectary\Services;

use Nectary\Models\Json_Feed;


/**
 * Service for the Json Feed. Defaults to using
 * get_curl_feed_data
 *
 * @extends Feed_Service
 */
class Json_Feed_Service extends Feed_Service {
	/**
	 * Get a Json_Feed for the given $url.
	 *
	 * @return Json_Feed
	 */
	public function get_feed( $url ) : Json_Feed {
		return new Json_Feed( $url, array( $this, 'get_curl_feed_data' ) );
	}

	/**
	 * @param  string $url
	 * @return string
	 * @throws \Exception
	 */
	public function get_curl_feed_data( $url ) : string {
		$session = curl_init( $url );

		curl_setopt( $session, CURLOPT_RETURNTRANSFER, true );

		$json = curl_exec( $session );

		if ( curl_error( $session ) ) {
			$error_message = 'JSON Call failed ';

			if ( \function_exists( 'curl_strerror' ) ) {
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
