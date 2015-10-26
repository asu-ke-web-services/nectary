<?php

namespace Nectary\Tests;

use Nectary\Services\Tidy_Service;

/**
 * @group service
 */
class Tidy_Service_Test extends \PHPUnit_Framework_TestCase {
  private $tidy_service;
  private $tidy_service_mock;

  protected function setUp() {
    $this->tidy_service = new Tidy_Service();
    $this->tidy_service_mock = $this->getMockBuilder( 'Nectary\Services\Tidy_Service' )
      ->setMethods( [ 'excerpt', 'normalize_html_string', 'tidy_parse_string'] )
      ->getMock();
  }

  function test_normalize_html_string_with_empty_string() {
    $this->assertEquals( '', $this->tidy_service->normalize_html_string(' ') );
  }

  function test_normalize_html_string_in_html_tags() {
    $this->assertEquals( 'content', $this->tidy_service->normalize_html_string( '<p> content </p>' ) );
  }

  function test_normalize_html_string_can_urldecode() {
    $this->assertEquals( '&', $this->tidy_service->normalize_html_string( '&amp;' ) );
  }

  function test_normalize_html_string_can_urldecode_and_return_html() {
    $this->assertEquals( '<b>word</b>', $this->tidy_service->normalize_html_string( '&lt;b&gt;word&lt;/b&gt;' ) );
  }

  function test_normalize_html_string_can_prevent_multiple_spaces() {
    $this->assertEquals( 'foo bar', $this->tidy_service->normalize_html_string( ' foo    bar' ) );
  }

  function test_excerpt_that_is_short_of_the_max_lenght() {
    $this->assertEquals( 'two words', $this->tidy_service->excerpt( 'two words', 50 ) );
  }

  function test_excerpt_with_the_exact_lenght() {
    $this->assertEquals( 'two words', $this->tidy_service->excerpt( 'two words', 2 ) );
  }

  function test_excerpt_that_is_too_long() {
    $this->assertEquals( 'two...', $this->tidy_service->excerpt( 'two words', 1 ) );
  }

  function test_excerpt_removes_html() {
    $this->assertEquals( 'two words', $this->tidy_service->excerpt( '<p>two words</p>', 2 ) );
  }

  function test_excerpt_doesnt_return_invalid_html() {
    $this->assertEquals( 'more than...', $this->tidy_service->excerpt( '<p>more than two words', 2 ) );
  }

  function test_excerpt_doesnt_return_too_many_periods() {
    $this->assertEquals( 'more than...', $this->tidy_service->excerpt( '<p>more than. two words', 2 ) );
  }

  function test_fallback_on_tidy_parse_string_library_missing() {
    // TODO: test the behavior when the global function tidy_parse_string isn't defined
  }
}
