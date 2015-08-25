<?php

/**
 * Command Utilities
 *
 * Contains alias functions
 */

/**
 * Handle a dispatch without knowing about the command's
 * details
 */
function dispatch( \Nectary\Command $command ) {
  $command->handle();
}
