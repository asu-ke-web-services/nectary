<?php

namespace Nectary;

/**
 * Objects that wish to return a
 * default decorate should implement
 * this interface
 */
interface Decoratable {
  /**
   * Return a created decorator.
   *
   * @return Decorator
   */
  public function decorate();
}
