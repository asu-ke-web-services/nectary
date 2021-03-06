<?php

namespace Nectary\Views;

use Handlebars\Arguments;
use Handlebars\Handlebars;
use Handlebars\Loader\FilesystemLoader;
use Handlebars\Loader\StringLoader;
use Handlebars\StringWrapper;
use Nectary\Configuration;
use Nectary\View;

/**
 * View Handlebars
 */
abstract class Handlebars_View extends View {
	protected $model;
	protected $engine;
	protected $view_root = '/';

	/**
	 * The constructor will automatically try to determine
	 * where the views are. The $view_root is the folder path
	 * under the $path_to_views folder path.
	 *
	 * @constructor
	 * @param string|bool  $view_root     : Use false if you are not rendering views from files
	 * @param string|array $path_to_views : Use to override the path to the views
	 *
	 * @throws \RuntimeException
	 * @throws \InvalidArgumentException
	 */
	protected function __construct( $view_root = '', $path_to_views = null ) {
		if ( false === $view_root ) {
			// False means we are not loading from a template!
			$loader       = new StringLoader();
			$this->engine = new Handlebars( array( 'loader' => $loader ) );
		} else {
			if ( '' !== $view_root ) {
				$this->view_root = $view_root . '/';
			}

			if ( null == $path_to_views ) {
				$path_to_views = Configuration::get_instance()->get( 'path_to_views' );
			}

			$this->engine = new Handlebars(
				array(
					'loader'          => new FilesystemLoader( $path_to_views ),
					'partials_loader' => new FilesystemLoader( $path_to_views ),
				)
			);
		}

		$this->add_engine_helpers();
	}

	protected function render( &$template, &$context, $additional_context = array(), $include_context = false ) {
		if ( $include_context ) {
			$that               = $context->get( 'this' );
			$additional_context = array_merge( $that, $additional_context );
		}

		$context->push( $additional_context );
		$rendered = $template->render( $context );
		$context->pop();

		return $rendered;
	}

	private function add_engine_helpers() {
		$this->add_html_special_chars_handler();
		$this->add_conditional_operators();
		$this->add_strip_tags();
		$this->add_config();
		$this->add_slugify_helper();
	}

	private function add_config() {
		$this->engine->addHelper(
			'config',
			function ( $template, $context, $args ) {
					$handlebars_arguments = new Arguments( $args );
					$arguments            = $handlebars_arguments->getPositionalArguments();

					$key = $arguments[0];

				if ( \count( $arguments ) > 1 ) {
					$default = $arguments[1];
					$value   = Configuration::get_instance()->get( $key, $default );
				} else {
					$value = Configuration::get_instance()->get( $key );
				}

					return $value;
			}
		);
	}

	/**
	 * Slugify Helper - lets you slugify any string
	 */
	private function add_slugify_helper() {
		$this->engine->addHelper(
			'slugify',
			function ( $template, $context, $args ) {
					$arg = $context->get( $args );
					return slugify( $arg );
			}
		);
	}

	/**
	 * No Tags Formatter
	 *
	 * If we have a alt rsvp add the link to that alternate rsvp to the
	 * context.
	 */
	private function add_strip_tags() {
		$this->engine->addHelper(
			'strip_tags',
			function ( $template, $context, $args ) {
					$arg = $context->get( $args );
					return strip_tags( $arg );
			}
		);
	}

	/**
	 * Helper function html_special_chars
	 *
	 * Takes in a string as a parameter.  The string is a key to the template model
	 */
	private function add_html_special_chars_handler() {
		$this->engine->addHelper(
			'html_special_chars',
			function ( $template, $context, $args ) {
					$that = $context->get( 'this' );

				if ( array_key_exists( $args, $that ) ) {
					return htmlspecialchars( $that[ $args ] );
				}
			}
		);
	}

	/**
	 * Helper function if_cond
	 */
	private function add_conditional_operators() {
		$this->engine->addHelper(
			'if_cond',
			function ( $template, $context, $args ) {
					$that = $context->get( 'this' );

					$handlebars_arguments = new Arguments( $args );
					$arguments            = $handlebars_arguments->getPositionalArguments();

					$value_a  = $arguments[0];
					$operator = $arguments[1];
					$value_b  = $arguments[2];

					$value_a = $this->get_real_value( $value_a, $that );
					$value_b = $this->get_real_value( $value_b, $that );

				if ( $this->is_conditional_true( $value_a, $value_b, $operator ) ) {
					$template->setStopToken( 'else' );
					$buffer = $this->render( $template, $context, array(), true );
					$template->setStopToken( false );
					$template->discard( $context );
				} else {
					$template->setStopToken( 'else' );
					$template->discard( $context );
					$template->setStopToken( false );
					$buffer = $this->render( $template, $context, array(), true );
				}

					return $buffer;
			}
		);
	}

	private function is_conditional_true( $value_a, $value_b, $operator ) {
		// @codingStandardsIgnoreStart
		switch ( $operator ) {
			case '==':
				return $value_a == $value_b;
			case '===':
				return $value_a === $value_b;
			case '<':
				return $value_a < $value_b;
			case '<=':
				return $value_a <= $value_b;
			case '>':
				return $value_a > $value_b;
			case '>=':
				return $value_a >= $value_b;
			case '&&':
				return $value_a && $value_b;
			case '||':
				return $value_a || $value_b;
			default:
				return false;
		}
		// @codingStandardsIgnoreEnd
	}

	private function get_real_value( $value, $context ) {
		if ( $value instanceof StringWrapper ) {
			return $this->get_real_value( '' . $value, $context );
		}

		if ( array_key_exists( $value, $context ) ) {
			return $context[ $value ];
		}

		// string to real type
		if ( is_numeric( $value ) ) {
			return $value + 0;
		}

		if ( \in_array( $value, [ 'true', 'false' ], false ) ) {
			return ( 'true' === $value );
		}

		return $value;
	}
}
