<?php

namespace Nectary;

use Nectary\Factories\View_Factory;
use Nectary\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * View Factory Test
 *
 * @group view_factory
 * @group view
 */
class View_Factory_Test extends TestCase {
	protected $view_factory;

	protected function setUp() {
		$this->view_factory = new View_Factory( 'na' );
		// prevent any configuration changes going between tests
    Configuration::set_configuration_path();
    $instance_1 = Configuration::get_instance();
    $instance_1->attributes = [ 'TEST' => 'Test Value' ];
	}

	public function test_can_add_data_and_calls() {
		$result = $this->view_factory->add_data(
				array(
					'test' => 'data'
				)
		);

		$this->assertEquals( $this->view_factory, $result );
	}

	public function test_can_add_head_chain_calls() {
		$result = $this->view_factory->add_head(
				array(
					'test' => 'data'
				)
		);

		$this->assertEquals( $this->view_factory, $result );
	}

	public function test_build_uses_given_configuration() {
		Configuration::get_instance()->set( 'path_to_views', 'test_path' );

		$mock = create_function_mock( $this, 'glob', 1 );
		$mock->with( 'test_path/na.*' )
		->will( $this->returnValue( [] ) );

		$this->view_factory->build();
	}

	public function test_build_uses_overridden_path_configuration() {
		Configuration::get_instance()->set( 'path_to_views', 'global_path' );
		$this->view_factory = new View_Factory( 'view.na' , 'overridden_path');

		$mock = create_function_mock( $this, 'glob', 1 );
		$mock->with( 'overridden_path/view/na.*' )
		 ->will( $this->returnValue( [] ) );

		$this->view_factory->build();
	}

	public function test_build_uses_dot_notation_for_paths() {
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

	public function test_build_uses_multiple_paths_given_configuration() {
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
