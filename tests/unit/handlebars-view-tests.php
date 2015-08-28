<?php

namespace Nectary\Tests;

use Nectary\Views\Handlebars_View;

/**
 * Test the handlebars view class in the framework
 *
 * @group view
 */
class Handlebars_View_Test extends \PHPUnit_Framework_TestCase {
  function test_exists() {
    $this->assertEquals( 'Nectary\Views\Handlebars_View', Views\Handlebars_View::class );
  }

  function test_loads_in_files_from_different_view_roots() {
    // TODO
  }

  function test_renders_basic_handlebars_templates() {
    // TODO
  }

  function test_renders_conditionals_correctly() {
    // TODO
  }

  function test_special_conditional_helper() {
    // TODO
  }

  function test_special_html_special_chars_helper() {
    // TODO
  }

  function test_add_strip_tags_helper() {
    // TODO
  }
}
