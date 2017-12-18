<?php

namespace Nectary\Services;

/**
 * Feed Services should implement a public
 * get_feed( $url ) to abstract away how a
 * particular feed is retreived.
 */
abstract class Feed_Service {
	abstract public function get_feed( string $url );
}
