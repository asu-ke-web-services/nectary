<?php

namespace Nectary\Tests;

use Nectary\Daos\Select_SQL_Query_Builder;

/**
 * SQL Query Builder Test
 *
 * @group daos
 */
class Sql_Query_Builder_Test extends \PHPUnit_Framework_TestCase {
  public function test_constructor() {
    $builder = new Select_SQL_Query_Builder();
    $this->assertInstanceOf( 'Nectary\Daos\Select_SQL_Query_Builder', $builder );
  }

  public function test_that_a_simple_query_is_executeable() {
    $builder = new Select_SQL_Query_Builder();
    $builder->add_columns( 'people.person_id' );
    $builder->from( 'people' );
    $statement = $builder->get_sql();

    $statement = preg_replace( '/\s+/', ' ', $statement );

    $this->assertContains( 'SELECT people.person_id FROM people WHERE 1=1 LIMIT 500', $statement );
  }

  public function test_that_a_bare_query_is_executeable() {
    $builder = new Select_SQL_Query_Builder();
    $builder->from( 'people' );
    $statement = $builder->get_sql();

    $statement = preg_replace( '/\s+/', ' ', $statement );

    $this->assertEquals( 'SELECT * FROM people WHERE 1=1 LIMIT 500', $statement );
  }

  public function test_where_clauses_incorrectly() {
    // this should fail because we are using the where clause incorrectly, we are giving it a statement not a clause
    $builder = new Select_SQL_Query_Builder();
    $builder->from( 'people' );
    $builder->where( 'AND 1<>1' );
    $statement = $builder->get_sql();

    $statement = preg_replace( '/\s+/', ' ', $statement );

    $this->assertEquals( 'SELECT * FROM people WHERE 1=1 AND 1<>1 LIMIT 500', $statement );
  }

  public function test_and_where_clauses_correctly() {
    // this should work because we are passing a statement to the and_where for it to build the clause
    $builder = new Select_SQL_Query_Builder();
    $builder->from( 'people' );
    $builder->and_where( '1=1' );
    $statement = $builder->get_sql();

    $statement = preg_replace( '/\s+/', ' ', $statement );

    $this->assertEquals( 'SELECT * FROM people WHERE 1=1 AND 1=1 LIMIT 500', $statement );
  }

  public function test_or_where_clauses_correctly() {
    // this should work because we are passing a statement to the or_where for it to build the clause
    $builder = new Select_SQL_Query_Builder();
    $builder->from( 'people' );
    $builder->or_where( '1=1' );
    $statement = $builder->get_sql();

    $statement = preg_replace( '/\s+/', ' ', $statement );

    $this->assertEquals( 'SELECT * FROM people WHERE 1=1 OR 1=1 LIMIT 500', $statement );
  }

  public function test_add_columns_can_take_multiple_types_and_maintain_array() {
    $builder = new Select_SQL_Query_Builder();
    $builder->add_columns( 'people.person_id' );
    $builder->add_columns( array( 'people.first_name' ) );
    $builder->add_columns( 'people.last_name, people.middle_name' );
    $builder->from( 'people' );
    $statement = $builder->get_sql();

    $statement = preg_replace( '/\s+/', ' ', $statement );

    $this->assertEquals( 'SELECT people.person_id, people.first_name, people.last_name, people.middle_name FROM people WHERE 1=1 LIMIT 500', $statement );
  }

  public function test_that_a_more_complicated_query_is_executeable() {
    $builder = new Select_SQL_Query_Builder();
    $builder->add_columns( 'people.*' );
    $builder->from( 'people' );
    $builder->limit( 1 );
    $builder->order_by( 'people.person_id DESC' );
    $builder->joins( 'LEFT JOIN groups_people ON (groups_people.person_id = people.person_id)' );
    $builder->add_columns( 'groups_people.*' );
    $builder->where( 'AND people.person_id <> 1' );
    $statement = $builder->get_sql();

    $statement = preg_replace( '/\s+/', ' ', $statement );

    $this->assertEquals( 'SELECT people.*, groups_people.* FROM people LEFT JOIN groups_people ON (groups_people.person_id = people.person_id) WHERE 1=1 AND people.person_id <> 1 ORDER BY people.person_id DESC LIMIT 1', $statement );
  }

  public function test_group_by() {
    $builder = new Select_SQL_Query_Builder();
    $builder->group_by( 'column-to-group-by' );
    $sql = $builder->get_sql();

    $sql = preg_replace( '/\s+/', ' ', $sql );

    $this->assertContains( ' GROUP BY column-to-group-by ', $sql );
  }

  public function test_group_by_multiple() {
    $builder = new Select_SQL_Query_Builder();
    $builder->group_by( 'first-column-to-group-by' );
    $builder->group_by( 'second-column-to-group-by' );
    $sql = $builder->get_sql();

    $sql = preg_replace( '/\s+/', ' ', $sql );

    $this->assertContains( ' GROUP BY first-column-to-group-by, second-column-to-group-by', $sql );
  }
}
