<?php

namespace Nectary\Factories;

use Nectary\Factory;
use Nectary\Configuration;
use Nectary\Response;
use Nectary\Models\Presentable_Model;
use Nectary\Views\Simple_Handlebars_View;

/**
 * This factory is slightly different because
 * it has chainable methods.
 *
 * View Names should be 'object'-like 'paths' to the
 * view that you wish to display. For example, if there
 * is a handlebars view in `src/views/events/blurb.handlebars`,
 * then the $view_name would be `events.blurb`.
 *
 * Given the path to the views, this factory
 * will create the appropriate view implemention
 * render the file
 *
 * @extends Factory
 */
class View_Factory extends Factory {
	protected $view_name;
	protected $view_data;
	protected $head_data;
	protected $path_to_views;

	/**
	 * Set up Factory data
	 *
	 * @constructor
	 * @param $view_name String The 'path'-like string to the view.
	 * @param $path_to_views String An actual directory path that will override
	 *  the global nectary configuration.
	 */
	public function __construct( $view_name, $path_to_views = null ) {
		$this->view_name     = $view_name;
		$this->view_data     = [];
		$this->head_data     = [];
		$this->path_to_views = $path_to_views;
	}

	/**
	 * Chainable
	 *
	 * Inject data into the view. You can pass an array, or
	 * named array. This data will be added to
	 * the view's data model.
	 *
	 * All of the following are valid data:
	 *
	 * Inject the $event into the 'event' context:
	 * ```
	 * $view->add_data(
	 *    array(
	 *     'event' => $event->present()
	 *    )
	 * );
	 * ```
	 *
	 * Inject the $event into the global context:
	 * ```
	 * $view->add_data(
	 *    array(
	 *     '_event' => $event->present()
	 *    )
	 * );
	 * ```
	 *
	 * Inject the $event into the global context:
	 * ```
	 * $view->add_data( $event->present() );
	 * ```
	 *
	 * @param  mixed        $data
	 * @return View_Factory
	 */
	public function add_data( $data ) {
		if ( \is_array( $data ) ) {
			foreach ( $data as $key => $value ) {
				$data_to_add = $value;
				if ( $value instanceof Presentable_Model ) {
					$data_to_add = $value->present();
				} elseif ( \is_object( $value ) ) {
					$data_to_add = json_decode( json_encode( $value ), true );
				}

				if ( 0 === strpos( $key, '_' ) ) {
					$this->view_data = array_merge( $this->view_data, $data_to_add );
				} else {
					$this->view_data[ $key ] = $value;
				}
			}
		}

		return $this;
	}

	/**
	 * Chainable
	 *
	 * Add data that should be in the head (aka add_header).
	 *
	 * @param  mixed $data
	 * @return View_Factory
	 */
	public function add_head( $data ) {
		$this->head_data = $data;

		if ( \is_array( $data ) ) {
			$this->head_data = array_merge( $this->head_data, $data );
		}

		return $this;
	}

	/**
	 * By default, this will create a View instance
	 * and will generate a Response from that View
	 * using the data and head that has been provided.
	 *
	 * @override
	 */
	public function build() {
		$view_root      = '';
		$template_name  = $this->get_template_name();
		$file_extension = $this->get_file_extension( $view_root, $template_name );
		$view_data      = $this->view_data;
		$content        = '';

		if ( 'handlebars' === $file_extension ) {
			$view = new Simple_Handlebars_View(
				$view_root,
				$template_name,
				function ( $engine ) use ( $template_name, $view_data ) {
						return $engine->render(
							$template_name,
							$view_data
						);
				},
				$this->path_to_views
			);
			$content = $view->output();
		}

		return new Response(
			array(
				'content' => $content,
				'head'    => $this->head_data,
			)
		);
	}


	/**
	 * Given the 'object'-like path, determine the template
	 * name for the view.
	 * eg: $this->view_name = 'blah.foo'
	 *   get_template_name() returns 'blah/foo'
	 */
	private function get_template_name() {
		return str_replace( '.', '/', $this->view_name );
	}

	/**
	 * Get the path to the views, either the configuration path or the overridden path
	 */
	private function get_path_to_views() {
		if ( null !== $this->path_to_views ) {
			return $this->path_to_views;
		}

		return Configuration::get_instance()->get( 'path_to_views' );
	}

	/**
	 * Find the first file extension that matches for this view template
	 *
	 * @param  string $view_root
	 * @param  string $template_name
	 * @return mixed
	 */
	private function get_file_extension( $view_root, $template_name ) {
		$paths = to_array( $this->get_path_to_views() );
		// get first element in array
		$path = reset( $paths );

		if ( ! empty( $view_root ) ) {
			$path .= '/' . $view_root;
		}

		$path .= '/' . $template_name;

		$files = glob( $path . '.*' );
		//return pathinfo of first $files element
		return pathinfo( reset( $files ), PATHINFO_EXTENSION );
	}
}
