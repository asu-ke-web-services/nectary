<?php

namespace Nectary\Tests\Mocks;

class Dif_Test_Object {
  public function __construct() {}
  public function exclaim() {
    return 'wow';
  }
}

class Dif_Nested_Test_Object {
  public function __construct( Dif_Test_Object $d ) {
    $this->d = $d;
  }
  public function exclaim() {
    return $this->d->exclaim() . '!';
  }
}
