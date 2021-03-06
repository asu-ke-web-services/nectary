<?php

namespace Nectary\Models;

use Nectary\Feed;

/**
 * Json Feed class for pulling and parsing
 * JSON feeds from the web
 *
 * @implements Feed
 */
class Json_Feed implements Feed {
	private $url;
	private $items;
	private $feed_callback;

	public function __construct( $url, $feed_callback = null ) {
		$this->url           = $url;
		$this->feed_callback = $feed_callback;
	}

	/**
	 * Pull the items from the web and store them
	 * in this object
	 *
	 * @param  int|bool   $look_at  use to grab items from a section of the json
	 * @throws \Exception
	 */
	public function retrieve_items( $look_at = false ) {
		$json = \call_user_func( $this->feed_callback, $this->url );
		$raw  = $this->parse_feed( $json );

		$this->items = $raw;
		if ( $look_at !== false ) {
			$this->items = $raw[ $look_at ];
		}
	}

	public function sort_by_date( string $order = 'asc' ) {
		// TODO
	}

	/**
	 * @return array
	 */
	public function get_items() : array {
		return $this->items;
	}

	public function set_items( array $items ) {
		$this->items = $items;
	}

	/**
	 * @return array
	 */
	public function get_unique_items() : array {
		$unique = [];

		foreach ( $this->items as $i => $item_a ) {
			$duplicate = false;
			foreach ( $this->items as $j => $item_b ) {
				if ( $i !== $j ) {
					// Loose equality on purpose
					if ( $item_a == $item_b ) {
						// Only count a duplicate if it is BEHIND the current index
						if ( $j < $i ) {
							// Duplicate found
							$duplicate = true;
							break;
						}
					}
				}
			}
			if ( ! $duplicate ) {
				$unique[] = $this->items[ $i ];
			}
		}

		return $unique;
	}

	/**
	 * @param  string $json
	 * @return mixed
	 * @throws \Exception
	 */
	private function parse_feed( string $json ) {
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
		$has_error = array_key_exists( 'errors', $json )
		&& array_key_exists( 0, $json['errors'] )
		&& array_key_exists( 'message', $json['errors'][0] );

		if ( $has_error ) {
			return $json['errors'][0]['message'];
		}
	}
}
