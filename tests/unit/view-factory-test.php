<?php

namespace Nectary;

use Nectary\Factories\View_Factory;
use Nectary\Configuration;

/**
 * View Factory Test
 *
 * @group view_factory
 * @group view
 */
class View_Factory_Test extends \PHPUnit_Framework_TestCase {
  protected $view_factory;

  function setUp() {
    $this->view_factory = new View_Factory( 'na' );
    // prevent any configuration changes going between tests 
    Configuration::get_instance()->reset();
  }

  function test_can_add_data_and_calls() {
    $result = $this->view_factory->add_data(
        array(
          'test' => 'data'
        )
    );

    $this->assertEquals( $this->view_factory, $result );
  }

  function test_can_add_head_chain_calls() {
    $result = $this->view_factory->add_head(
        array(
          'test' => 'data'
        )
    );

    $this->assertEquals( $this->view_factory, $result );
  }

  function test_build_uses_given_configuration() {
    Configuration::get_instance()->set( 'path_to_views', 'test_path' );

    $mock = create_function_mock( $this, 'glob', 1 );
    $mock->with( 'test_path/na.*' )
    ->will( $this->returnValue( [] ) );

    $this->view_factory->build();
  }

  function test_build_uses_dot_notation_for_paths() {
    Configuration::get_instance()->set( 'path_to_views', 'test_path' );

    $mock = create_function_mock( $this, 'glob', 2 );
    $mock->withConsecutive(
      [ 'test_path/view/na.*' ],
      [ 'test_path/view/inner/na.*' ]
    )
    ->will(
        $this->onConsecutiveCalls( [], [] )
    );

    $view_factory = new View_Factory( 'view.na' );
    $view_factory->build();

    $view_factory = new View_Factory( 'view.inner.na' );
    $view_factory->build();
  }

  function test_build_uses_multiple_paths_given_configuration() {
    Configuration::get_instance()->add( 'path_to_views', 'test_path1' );
    Configuration::get_instance()->add( 'path_to_views', 'test_path2' );

    $mock = create_function_mock( $this, 'glob', 1 );
    $mock->with($this->logicalOr(
                 $this->equalTo('test_path1/na.*'  ),
                 $this->equalTo('test_path2/na.*'  )
             ))
            ->will($this->returnValue( [] ));

    $this->view_factory->build();
  }

}
