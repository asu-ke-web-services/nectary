<?php

namespace Nectary;

$mock_dictionary = [];
$mock_counts = [];

/**
 * Function mocker. Use to mock php functions
 */
function create_function_mock( $unit_reference, $name, $times = 1 ) {
  global $mock_dictionary;
  global $mock_counts;

  $mock_counts[ $name ] = $times;

  $mock = $unit_reference->getMockBuilder( 'Object' )
  ->setMethods( [ $name ] )
  ->getMock();

  $method = $mock->expects( $unit_reference->exactly( $times ) )
  ->method( $name );

  $mock_dictionary[ $name ] = $mock;

  return $method;
}

function __perform( $name, $callback ) {
  global $mock_dictionary;
  global $mock_counts;

  if ( array_key_exists( $name, $mock_dictionary ) ) {
    $mock_counts[ $name ]--;
    $value = $callback( $mock_dictionary[ $name ] );

    if ( $mock_counts[ $name ] < 1 ) {
      unset( $mock_dictionary[ $name ] );
      unset( $mock_counts[ $name ] );
    }

    return $value;
  }
}

// ===============
// Implementations
// ===============

function dispatch( $command ) {
  __perform( 'dispatch', function( $mock ) use ( $command ) {
      $mock->dispatch( $command );
  } );
}

function mail( $to, $subject, $body, $header ) {
  __perform( 'mail', function( $mock ) use ( $to, $subject, $body, $header ) {
      $mock->mail( $to, $subject, $body, $header );
  } );
}

function file_exists( $file_path ) {
  return __perform( 'file_exists', function ( $mock ) use ( $file_path ) {
      return $mock->file_exists( $file_path );
  } );
}

function file_get_contents( $file_path ) {
  return __perform( 'file_get_contents', function( $mock ) use ( $file_path ) {
      return $mock->file_get_contents( $file_path );
  } );
}
