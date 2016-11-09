<?php

namespace Nectary\Views;

use \Handlebars\Handlebars;
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
   * @param $view_root String|Boolean Use false if you are not using views
   * @param $path_to_view String|Array Use to override the path to the views
   */
  protected function __construct( $view_root = '', $path_to_views = null ) {
    if ( false === $view_root ) {
      // False means we are not loading from a template!
      $loader = new \Handlebars\Loader\StringLoader();
      $this->engine = new \Handlebars\Handlebars( array( 'loader' => $loader ) );
    } else {
      if ( '' !== $view_root ) {
        $this->view_root = $view_root . '/';
      }

      if ( null == $path_to_views ) {
        $path_to_views = Configuration::get_instance()->get( 'path_to_views' );
      }

      if ( is_array( $path_to_views ) ) {
        $paths_to_load_views = array_map( function( $item ) {
            return $item . '/' . $this->view_root;
        }, $path_to_views );
      } else {
        $paths_to_load_views = $path_to_views . '/' . $this->view_root;
      }

      $this->engine = new \Handlebars\Handlebars(
          array(
            'loader' => new \Handlebars\Loader\FilesystemLoader( $paths_to_load_views ),
            'partials_loader' => new \Handlebars\Loader\FilesystemLoader( $paths_to_load_views ),
          )
      );
    }

    $this->add_engine_helpers();
  }

  protected function render( &$template, &$context, $additional_context = array(), $include_context = false ) {
    if ( $include_context ) {
      $that = $context->get( 'this' );
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
  }

  private function add_config() {
    $this->engine->addHelper(
        'config',
        function ( $template, $context, $args ) {
          $handlebars_arguments = new \Handlebars\Arguments( $args );
          $arguments            = $handlebars_arguments->getPositionalArguments();

          $key = $arguments[0];

          if ( count( $arguments ) > 1 ) {
            $default = $arguments[1];
            $value = Configuration::get_instance()->get( $key, $default );
          } else {
            $value = Configuration::get_instance()->get( $key );
          }

          return $value;
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

          $handlebars_arguments = new \Handlebars\Arguments( $args );
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
    if ( $value instanceof \Handlebars\StringWrapper ) {
      return $this->get_real_value( '' . $value, $context );
    } else if ( array_key_exists( $value, $context ) ) {
      return $context[ $value ];
    } else {
      // Now string to real type
      if ( is_numeric( $value ) ) {
        return $value + 0;
      } else if ( in_array( $value, [ 'true', 'false' ] ) ) {
        return ( 'true' === $value ? true : false );
      } else {
        return $value;
      }
    }
  }
}
