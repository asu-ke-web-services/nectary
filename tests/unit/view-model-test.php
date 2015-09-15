<?php

namespace Nectary\Tests;

use Nectary\Models\View_Model;

/**
 * @group model
 */
class View_Model_Test extends \PHPUnit_Framework_TestCase {
  function __construct() {

    parent::__construct( 'View Model Test' );
  }

  protected function teardown() {

    parent::tearDown();
  }

  function test_constructor() {

    $view_model = new View_Model();
    $this->assertInstanceOf( 'Nectary\Models\View_Model', $view_model );
  }

  function test_attributes() {

    $view_model = new View_Model(
        [
          'http_header' => 'http header',
          'head'        => 'head',
          'content'     => 'content',
          'footer'      => 'footer',
          ]
    );

    $this->assertEquals( $view_model->http_header, 'http header' );
    $this->assertEquals( $view_model->head,        'head' );
    $this->assertEquals( $view_model->content,     'content' );
    $this->assertEquals( $view_model->footer,      'footer' );
  }

  function test_404() {

    $four_oh_four = View_Model::$error_404;
    $this->assertEquals( array( 'HTTP/1.0 404 Not Found - Archive Empty' ), $four_oh_four->http_header );
    $this->assertEquals( 'Not Found', $four_oh_four->content );

  }
}
