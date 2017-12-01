<?php

namespace Nectary\Models;

/**
 * Data model
 */
abstract class Data_Model {
  public static $primary_key;
  public static $table_name;

  /**
   * This is a helpful method for constructing objects from an
   * associated array of values
   */
  public function __construct( $array_of_values = array() ) {
    foreach ( $array_of_values as $fieldname => $value ) {
      if ( property_exists( $this, $fieldname ) ) {
        $this->$fieldname = $value;
      }
    }
  }

  /**
   * Getter
   */
  public function __get( $property ) {
    if ( property_exists( $this, $property ) ) {
      return $this->$property;
    }
  }

  /**
   * Setter - only allow properties that exist to be set
   */
  public function __set( $property, $value ) {
    if ( property_exists( $this, $property ) ) {
      $this->$property = $value;
    }
    return $this;
  }

  /**
   * Models should implement get_minimal_columns
   */
  public static function get_minimal_columns() {
    return '';
  }

  /**
   * Models should implement get_all_columns
   */
  public static function get_all_columns() {
    return '';
  }
}
