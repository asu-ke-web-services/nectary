<?php

namespace Nectary\Services;

abstract class Feed_Service {
  abstract public function get_feed( $url );
}
