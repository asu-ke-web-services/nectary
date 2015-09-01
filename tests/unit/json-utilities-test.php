<?php

namespace Nectary\Tests;

use Nectary\Utilities\Json_Utilities;

/**
 * Test the json utilities in the framework
 *
 * @group utility
 */
class Json_Utilities_Test extends \PHPUnit_Framework_TestCase {
  function test_will_provide_value() {
    $raw = "
      {
        \"key\" : \"value\"
      }
    ";

    $json = json_decode( $raw, true );

    $value = Json_Utilities::get( $json, 'key' );

    $this->assertEquals( 'value', $value );
  }

  function test_will_walk_along_json() {
    $raw = "
      {
        \"key1\" : {
          \"key2\" : {
            \"key3\" : \"value\"
          }
        }
      }
    ";

    $json = json_decode( $raw, true );

    $value = Json_Utilities::get( $json, 'key1.key2.key3' );
    $this->assertEquals( 'value', $value );
  }

  function test_will_quietly_fail_while_walking() {
    $raw = "
      {
        \"key1\" : {
          \"key2\" : {
            \"key3\" : \"value\"
          }
        }
      }
    ";

    $json = json_decode( $raw, true );

    $value = Json_Utilities::get( $json, 'key1.key2.keyNope' );
    $this->assertEquals( [ 'key3' => 'value' ], $value );
  }

  function test_will_return_real_value_when_providing_a_default() {
    $raw = "
      {
        \"key1\" : {
          \"key2\" : {
            \"key3\" : \"value\"
          }
        }
      }
    ";

    $json = json_decode( $raw, true );

    $value = Json_Utilities::getOrDefault( $json, 'key1.key2.key3', '???' );
    $this->assertEquals( 'value', $value );
  }

  function test_will_provide_default_if_fail() {
    $raw = "
      {
        \"key1\" : {
          \"key2\" : {
            \"key3\" : \"value\"
          }
        }
      }
    ";

    $json = json_decode( $raw, true );

    $value = Json_Utilities::getOrDefault( $json, 'key1.key2.keyNope', '???' );
    $this->assertEquals( '???', $value );
  }
}
