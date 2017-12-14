<?php

namespace Nectary\Models;

use Nectary\Feed;

/**
 * Rss Feed class for pulling and parsing
 * RSS feeds from the web
 *
 * @implements Feed
 */
class Rss_Feed implements Feed {
	private $url;
	private $items;
	private $feed_callback;

	public function __construct( string $url = '', string $feed_callback = null ) {
		$this->url           = $url;
		$this->feed_callback = $feed_callback;
	}

	/**
	 * @throws \Exception
	 */
	public function retrieve_items() {
		$feed = \call_user_func( $this->feed_callback, $this->url );

		if ( \function_exists( 'is_wp_error' ) && is_wp_error( $feed ) ) {
			throw new \Exception( 'Feed could not be loaded' );
		}

		$this->items = $feed->get_items( 0 );
	}

	// @codingStandardsIgnoreStart
	public function sort_by_date( string $order = 'asc' ) {
		usort( $this->items, function ( $a, $b ) use ( $order ) {
				$a_start_date = strtotime( $a->get_date() );
				$b_start_date = strtotime( $b->get_date() );

				if ( $a_start_date == $b_start_date ) {
				return 0;
				}

				if ( $order === 'asc' ) {
				return ( $a_start_date > $b_start_date ) ? 1 : -1;
				}

				return ( $a_start_date < $b_start_date ) ? 1 : -1;
		} );
	}
	// @codingStandardsIgnoreEnd

	public function get_items() : array {
		return $this->items;
	}

	public function set_items( array $items ) {
		$this->items = $items;
	}

	public function get_unique_items() : array {
		// TODO
	}
}
