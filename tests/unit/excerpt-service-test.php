<?php

namespace Nectary\Tests;

use Nectary\Services\Excerpt_Service;

use PHPUnit\Framework\TestCase;

/**
 * @group service
 */
class Excerpt_Service_Test extends TestCase {
	private $excerpt_service;
	private $excerpt_service_mock;

	protected function setUp() {
		$this->excerpt_service = new Excerpt_Service();
		$this->excerpt_service_mock = $this->getMockBuilder( 'Nectary\Services\Excerpt_Service' )
			->setMethods( [ 'excerpt', 'normalize_html_string', 'tidy_parse_string'] )
			->getMock();
	}

	public function test_normalize_html_string_with_empty_string() {
		$this->assertEquals( '', $this->excerpt_service->normalize_html_string(' ') );
	}

	public function test_normalize_html_string_in_html_tags() {
		$this->assertEquals( 'content', $this->excerpt_service->normalize_html_string( '<p> content </p>' ) );
	}

	public function test_normalize_html_string_can_urldecode() {
		$this->assertEquals( '&', $this->excerpt_service->normalize_html_string( '&amp;' ) );
	}

	public function test_normalize_html_string_can_urldecode_and_return_html() {
		$this->assertEquals( '<b>word</b>', $this->excerpt_service->normalize_html_string( '&lt;b&gt;word&lt;/b&gt;' ) );
	}

	public function test_normalize_html_string_can_prevent_multiple_spaces() {
		$this->assertEquals( 'foo bar', $this->excerpt_service->normalize_html_string( ' foo    bar' ) );
	}

	public function test_excerpt_that_is_short_of_the_max_lenght() {
		$this->assertEquals( 'two words', $this->excerpt_service->excerpt( 'two words', 50 ) );
	}

	public function test_excerpt_with_the_exact_lenght() {
		$this->assertEquals( 'two words', $this->excerpt_service->excerpt( 'two words', 2 ) );
	}

	public function test_excerpt_that_is_too_long() {
		$this->assertEquals( 'two...', $this->excerpt_service->excerpt( 'two words', 1 ) );
	}

	public function test_excerpt_removes_html() {
		$this->assertEquals( 'two words', $this->excerpt_service->excerpt( '<p>two words</p>', 2 ) );
	}

	public function test_excerpt_doesnt_return_invalid_html() {
		$this->assertEquals( 'more than...', $this->excerpt_service->excerpt( '<p>more than two words', 2 ) );
	}

	public function test_excerpt_doesnt_return_too_many_periods() {
		$this->assertEquals( 'more than...', $this->excerpt_service->excerpt( '<p>more than. two words', 2 ) );
	}
}
