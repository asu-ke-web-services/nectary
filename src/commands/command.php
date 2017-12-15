<?php

namespace Nectary;

/**
 * Commands are used as general task runners, specifically, they differ from
 * services and other types of actions in that they should not communicate
 * back to the caller.
 *
 * In fact, in order to ensure that a command adheres to this expectation,
 * a utility function called `dispatch` is provided which will handle
 * a command for you and will not return anything.
 *
 * The `dispatch` call is also preferred to calling `handle` directly since
 * Commands can be handled in different ways.
 */
abstract class Command {
	/**
	 * Handle the given command
	 */
	abstract public function handle();
}
