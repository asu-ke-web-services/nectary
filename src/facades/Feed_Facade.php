<?php

namespace Nectary\Facades;

use Nectary\Models\Feed;
use Nectary\Services\Feed_Service;

/**
 * Provide an interface for interacting with
 * Feed Services and Feeds
 */
class Feed_Facade {
	private $service;

	public function __construct( Feed_Service $service ) {
		$this->service = $service;
	}

	public function get_feed( $url ) {
		return $this->service->get_feed( $url );
	}

	/**
	 * Merge different Feed_Services together.
	 * Feeds passed in should have already had
	 * retrieve_items called on them.
	 *
	 * @param $feeds array<Feed>
	 */
	public function merge_feeds( array $feeds ) {
		$merged_feed = [ [] ]; // inner array covers case when no loops are made
		foreach ( $feeds as $feed ) {
			$merged_feed[] = $feed->get_items();
		}
		$merged_feed = array_merge( ...$merged_feed ); // much more efficient than performing array_merge inside loop

		$new_feed = $this->service->get_feed( '' );

		$new_feed->set_items( $merged_feed );

		return $new_feed;
	}

	public function unique_feed( Feed $feed ) {
		$new_feed = $this->service->get_feed( '' );

		$new_feed->set_items( $feed->get_unique_items() );

		return $new_feed;
	}
}
