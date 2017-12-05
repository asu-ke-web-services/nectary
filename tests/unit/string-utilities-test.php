<?php

namespace Nectary\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @group utility
 */
class String_Utilities_Test extends TestCase {
  function test_starts_with_returns_true() {
    $needle = '@';
    $haystack = '@asugreen';

    $this->assertTrue( starts_with( $haystack, $needle ) );
  }

  function test_starts_with_returns_false() {
    $needle = '@';
    $haystack = '.@asugreen';

    $this->assertFalse( starts_with( $haystack, $needle ) );
  }

  function test_ends_with_returns_true() {
    $needle = 'n';
    $haystack = '@asugreen';

    $this->assertTrue( ends_with( $haystack, $needle ) );
  }

  function test_ends_with_returns_false() {
    $needle = '@';
    $haystack = '@asugreen';

    $this->assertFalse( ends_with( $haystack, $needle ) );
  }

  function test_is_json_returns_true() {
    $good_json = '{}';

    $this->assertTrue( is_json( $good_json ) );
  }

  function test_is_json_returns_false() {
    $bad_json = '{';

    $this->assertFalse( is_json( $bad_json ) );

    $bad_json = (object)[];

    $this->assertFalse( is_json( $bad_json ) );
  }

  function test_slugify() {
    $this->assertEquals( 'blah-blah', slugify( 'blAh BLAH' ) );
    $this->assertEquals( 'n-a', slugify( '' ) );
    $this->assertEquals( 'blah-blah', slugify( 'blah-blah' ) );
    $this->assertEquals( 'blah-blah', slugify( 'blAh~!^BLAH' ) );
    $this->assertEquals( 'blah-blah', slugify( 'blAh BLAH!!!' ) );
  }

  function test_to_title_case() {
    $this->assertEquals( to_title_case( '' ), '', 'to_title_case should work for an empty string' );
    $this->assertEquals( to_title_case( null ), '', 'to_title_case should work for an an array' );
    $this->assertEquals( to_title_case( 'this is a string' ), 'This is a String', 'to_title_case should caplitalize the right words for a title' );
    $this->assertEquals( to_title_case( 'this is some, string, & string' ), 'This is Some, String, & String', 'strtotitle should allow other puncutation and symbols' );
  }

  function test_break_to_newline() {
    $this->assertEquals( "\n", br2nl( '<br>' ) );
    $this->assertEquals( "\n", br2nl( '<br/>' ) );
    $this->assertEquals( "\n", br2nl( '<br    />' ) );
    $this->assertEquals( "\n", br2nl( '<br    >' ) );
    $this->assertEquals( "\n\n", br2nl( '<br><br/>' ) );
  }

  function test_first_char_returns_the_first_character() {
    $this->assertEquals( 'a', first_char( 'alphabet' ) );
    $this->assertEquals( '', first_char( '' ) );
  }

  function test_xss() {
    $this->assertEquals( 'blah', xssafe( 'blah' ) );

    $this->assertNotEquals( '<script type="javascript">', xssafe( '<script type="javascript">' ) );
    $this->assertEquals( '&lt;script type=&quot;javascript&quot;&gt;', xssafe( '<script type="javascript">' ) );

    $this->assertNotEquals( "' onclick='alert(1)", xssafe( "' onclick='alert(1)" ) );
    $this->assertEquals( '&#039; onclick=&#039;alert(1)', xssafe( "' onclick='alert(1)" ) );
  }

  function test_email() {
    $valid_emails = array(
      'anything@anywhere.com',
      'something.somethingelse@something.net',
      'notnumb4rs@blah.blah.blah',
      );
    $invalid_emails = array(
      'anything@anthing@something.com',
      'justtext',
      'something with a space',
      'crap@crap',
      );
    foreach ( $valid_emails as $vaild_email ) {
      $result = valid_email( $vaild_email );
      $this->assertNotFalse( $result );
      if ( ! $result ) {
        echo "\nexpected this email to Pass, but it didn't :".$invaild_email."\n";
      }
    }
    foreach ( $invalid_emails as $invaild_email ) {
      $result = valid_email( $invaild_email );
      $this->assertFalse( $result );
      if ( $result ) {
        echo "\nexpected this email to fail, but it didn't :".$invaild_email."\n";
      }
    }
  }
}
