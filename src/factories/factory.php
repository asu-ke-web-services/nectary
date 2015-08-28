<?php

namespace Nectary;

/**
 * Factories should be used to build an object.
 *
 * Feel free to use factories as Virtual Constructors or
 * Builders. The interface behaves similarly to either
 * use cases.
 */
abstract class Factory {
  /**
   * Build an object from the Factory
   */
  abstract public function build();
}
