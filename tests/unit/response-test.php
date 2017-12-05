<?php

namespace Nectary\Tests;

use Nectary\Response;
use PHPUnit\Framework\TestCase;

/**
 * @group model
 */
class Response_Test extends TestCase {
  function __construct() {

    parent::__construct( 'View Model Test' );
  }

  protected function teardown() {

    parent::tearDown();
  }

  function test_constructor() {

    $response = new Response();
    $this->assertInstanceOf( Response::class, $response );
  }

  function test_attributes() {

    $response = new Response(
        array(
          'http_header' => 'http header',
          'head'        => 'head',
          'content'     => 'content',
          'footer'      => 'footer',
        )
    );

    $this->assertEquals( $response->http_header, 'http header' );
    $this->assertEquals( $response->head,        'head' );
    $this->assertEquals( $response->content,     'content' );
    $this->assertEquals( $response->footer,      'footer' );
  }

  function test_404() {

    $four_oh_four = Response::$error_404;
    $this->assertEquals( array( 'HTTP/1.0 404 Not Found - Archive Empty' ), $four_oh_four->http_header );
    $this->assertEquals( 'Not Found', $four_oh_four->content );

  }
}
