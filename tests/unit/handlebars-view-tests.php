<?php

namespace Nectary\Tests;

use Nectary\Views\Handlebars_View;
use PHPUnit\Framework\TestCase;

/**
 * Test the handlebars view class in the framework
 *
 * @group view
 */
class Handlebars_View_Test extends TestCase {
	public function test_exists() {
		$this->assertEquals( 'Nectary\Views\Handlebars_View', Views\Handlebars_View::class );
	}
}
