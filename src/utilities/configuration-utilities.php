<?php

/**
 * Configuration Utilities
 *
 * Contains alias functions
 */

use Nectary\Configuration;

/**
 * Handle configuration call without knowing about how
 * the Configuration object works
 */
function config( $key, $default = null ) {
  return Configuration::get_instance()->get( $key, $default );
}
