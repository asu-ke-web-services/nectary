<?php

namespace Nectary\Daos;

/**
 * Select_SQL_Query_Builder - Helper class for building PDO select statements
 *
 * @author Ryan Raub
 */
class Select_SQL_Query_Builder {

  /**
   * Internal state for this object
   */
  private $columns_to_select, $from, $joins, $where_clause, $values_to_bind, $order_by, $limit;

  /**
   * Sets up default state
   */
  public function __construct() {
    $this->columns_to_select = array();
    $this->from              = 'TABLE_NAME';
    $this->joins             = '';
    $this->where_clause      = ' 1=1 ';
    $this->values_to_bind    = array();
    $this->group_by          = array();
    $this->order_by          = '';
    $this->limit             = 500;
  }

  /**
   * This function takes either a string or an one dimensional array.
   * the string can be a single column name or multiple columns comma seperated.
   * @param $new_columns : string or array of column names to select
   */
  public function add_columns( $new_columns ) {
    $this->columns_to_select = array_merge( $this->columns_to_select, to_array( $new_columns ) );
  }

  /**
   * Mainly for debugging, probably wont need this
   * @return the current colums this query will select from
   */
  public function get_columns() {
    return $this->columns_to_select;
  }

  /**
   * For setting what table to select from, can only be one table. For joins see joins()
   * @param $table : string - name of table to select
   */
  public function from( $table ) {
    $this->from = $table;
  }

  /**
   * For adding a join, can be called multiple times for multiple joins.
   * @param $join_statement : string - sql fragment of the join statement
   */
  public function joins( $join_statement ) {
    $this->joins .= ' '.$join_statement . ' ';
  }

  /**
   * For setting a limit on the query other than the default
   * @param $new_limit : integer
   */
  public function limit( $new_limit ) {
    $this->limit = intval( $new_limit );
  }

  /**
   * For setting an order on the query other than the default. Currently Can't have multiple.
   * @param $order : string : eg: "first_name ASC"
   */
  public function order_by( $order ) {
    $this->order_by = $order;
  }

  /**
   * For setting an group by on the query other than the default.
   * @param $order : string : eg: "first_name"
   */
  public function group_by( $group ) {
    $this->group_by[] = $group;
  }

  /**
   * Adds a string to the where clause, it must start with an 'AND' or 'OR' to chain together
   * criteria.
   * @param $string : eg: "AND blah = foo"
   */
  public function where( $string ) {
    $this->where_clause .= ' ' . $string . ' ';
  }

  /**
   * Adds a string to the where clause with an 'AND'
   * @param $string : eg: "blah = foo"
   */
  public function and_where( $string ) {
    $this->where( ' AND ' . $string . ' ' );
  }

  /**
   * Adds a string to the where clause with an 'AND'
   * @param $string : eg: "blah = foo"
   */
  public function or_where( $string ) {
    $this->where( ' OR ' . $string . ' ' );
  }

  /**
   * Adds a variable to be bound to in the prepared statement
   * @param $name : string
   * @param $value : object
   */
  public function bind_value( $name, $value ) {
    $this->values_to_bind[ $name ] = $value;
  }

  /**
   * Uses the current state of the object to build the sql statement
   * @return the sql select statement as a string
   */
  public function get_sql() {
    if ( empty( $this->columns_to_select ) ) {
      // this should be the default behavor
      $this->columns_to_select = [ '*' ];
    }

    $from   = ' FROM ' . $this->from . ' ';
    $from  .= $this->joins;
    $from  .= ' WHERE ' . $this->where_clause;
    $select = 'SELECT ' . implode( ', ', $this->columns_to_select ) . $from;

    if ( ! empty( $this->group_by ) ) {
      $select .= ' GROUP BY '.implode( ', ', $this->group_by ).' ';
    }

    if ( $this->order_by !== '' ) {
      $select .= ' ORDER BY ' . $this->order_by . ' ';
    }

    $select .= ' LIMIT ' . $this->limit;
    return $select;
  }

  /**
   * Calls get_sql() to generate the sql and then creates the prepared statement
   * and binds its values.
   * @param $db : a pdo database connection
   * @return pdo::Statement object with the query and values bound
   */
  public function get_statement( $db ) {
    $statement = $db->prepare( $this->get_sql() );
    foreach ( $this->values_to_bind as $name => $value ) {
      $statement->bindValue( $name, $value );
    }
    return $statement;
  }
}
