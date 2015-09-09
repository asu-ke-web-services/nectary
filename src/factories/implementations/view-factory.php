<?php

namespace Nectary\Factories;

use Nectary\Factory;
use Nectary\Configuration;
use Nectary\Models\View_Model;

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
   */
  public function __construct( $view_name ) {
    $this->view_name = $view_name;
    $this->view_data = [];
    $this->head_data = [];
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
   * @param $data Mixed
   * @return $this
   */
  public function add_data( $data ) {
    if ( is_array( $data ) ) {
      foreach ( $data as $key => $value ) {
        $data_to_add = null;
        if ( $value instanceof Viewable_Model ) {
          $data_to_add = $value->present();
        } else if ( is_object( $value ) ) {
          $data_to_add = json_decode( json_encode( $value ), true );
        } else {
          $data_to_add = $value;
        }

        if ( '_' === substr( $key, 0, 1 ) ) {
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
   * @param $data Mixed
   * @return $this
   */
  public function add_head( $data ) {
    if ( is_array( $data ) ) {
      $this->head_data = array_merge( $this->head_data, $data );
    } else {
      $this->head_data = $data;
    }

    return $this;
  }

  /**
   * By default, this will create a View instance
   * and will generate a View_Model from that View
   * using the data and head that has been provided.
   *
   * @override
   */
  public function build() {
    $view_root      = $this->get_view_root();
    $template_name  = $this->get_template_name();
    $file_extension = $this->get_file_extension( $view_root, $template_name );
    $view_data      = $this->view_data;
    $content        = '';

    switch ( $file_extension ) {
      case 'handlebars':
        $view          = new Simple_Handlebars_View(
            $view_root,
            $template_name,
            function( $engine ) use ( $view_root, $template_name, $view_data ) {
              return $engine->render(
                  $template_name,
                  $view_data
              );
            }
        );
        $content = $view->output();
        break;
      default:
        break;
    }

    return new View_Model(
        array(
          'content' => $content,
          'head' => $this->head_data,
        )
    );
  }

  /**
   * Given the 'object'-like path, determine the root
   * directory for the view
   */
  private function get_view_root() {
    $parts = explode( '.', $this->view_name );

    if ( count( $parts ) <= 1 ) {
      return '';
    } else {
      // There may be more than one dot!

      $dot_position = strrpos( $this->view_name, '.' );
      $suffix = substr( $this->view_name, 0, $dot_position );
      $suffix = str_replace( '.', '/', $suffix );

      return $suffix;
    }
  }

  /**
   * Given the 'object'-like path, determine the template
   * name for the view
   */
  private function get_template_name() {
    $parts = explode( '.', $this->view_name );

    if ( count( $parts ) <= 1 ) {
      return $parts[0];
    } else {
      // There may be more than one dot!

      $dot_position = strrpos( $this->view_name, '.' );
      $suffix = substr( $this->view_name, $dot_position + 1 );

      return $suffix;
    }
  }

  private function get_file_extension( $view_root, $template_name ) {
    $path = Configuration::get_instance()->path_to_views;

    if ( ! empty( $view_root ) ) {
      $path .= '/' . $view_root;
    }

    $path .= '/' . $template_name;

    $files = glob( $path . '.*' );

    foreach ( $files as $filename ) {
      return pathinfo( $filename, PATHINFO_EXTENSION );
    }

    return '';
  }
}
