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

class Dif_Has_Abstract_Dependency_Test_Object {
	public $abstract_instance = false;
	public function __construct( Dif_Abstract_Test_Object $d = null ) {
		$this->abstract_instance = $d;
	}
}

abstract class Dif_Abstract_Test_Object {

}
