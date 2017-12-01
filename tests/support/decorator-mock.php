<?php

namespace Nectary\Tests\Mocks;

use Nectary\Decorators\Decoratable;
use Nectary\Decorators\Decorator;

class Decoratable_Test_Object implements Decoratable {
  public function decorate() {
    return new Decorator_Test_Object( $this );
  }
}

class Decorator_Test_Object extends Decorator {
}
