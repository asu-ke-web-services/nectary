<?php

namespace Nectary\Tests\Mocks;

use Nectary\Facades\Rss_Facade;

class Rss_Facade_Test_Object extends Rss_Facade {
  public function load_dependencies() {
    return 'noop';
  }
}
