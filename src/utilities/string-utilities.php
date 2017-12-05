<?php

if ( ! function_exists( 'starts_with' ) ) {
	/**
	 * Alias
	 */
	function starts_with( $haystack, $needle ) {
		return ! strncmp( $haystack, $needle, strlen( $needle ) );
	}
}

if ( ! function_exists( 'ends_with' ) ) {
	/**
	 * Alias
	 */
	function ends_with( $haystack, $needle ) {
		$haystack_length = strlen( $haystack );
		$needle_length   = strlen( $needle );
		if ( $needle_length > $haystack_length ) {
			return false;
		}

		return substr_compare( $haystack, $needle, -$needle_length ) === 0;
	}
}

if ( ! function_exists( 'is_json' ) ) {
	/**
	 * Check if a string is a valid json object
	 */
	function is_json( $string ) {
		if ( is_string( $string ) ) {
			json_decode( $string );
			return ( json_last_error() == JSON_ERROR_NONE );
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'slugify' ) ) {
	function slugify( $text ) {
		// cast to a string
		$text = (string) $text;

		// replace non letter or digits by -
		$text = preg_replace( '~[^\\pL\d]+~u', '-', $text );

		// trim
		$text = trim( $text, '-' );

		// transliterate
		$text = iconv( 'utf-8', 'us-ascii//TRANSLIT', $text );

		// lowercase
		$text = strtolower( $text );

		// remove unwanted characters
		$text = preg_replace( '~[^-\w]+~', '', $text );

		if ( empty( $text ) ) {
			return 'n-a';
		}

		return $text;
	}
}

if ( ! function_exists( 'to_title_case' ) ) {
	/**
	 * Converts $title to Title Case, and returns the result.
	 */
	function to_title_case( $title ) {
		$title = strval( $title );
		// Our array of 'small words' which shouldn't be capitalised if
		// they aren't the first word. Add your own words to taste.
		$small_words = array(
			'a',
			'and',
			'an',
			'at',
			'but',
			'by',
			'else',
			'for',
			'from',
			'if',
			'in',
			'into',
			'is',
			'nor',
			'of',
			'off',
			'on',
			'or',
			'over',
			'out',
			'the',
			'then',
			'to',
			'when',
			'with',
		);

		// Split the string into separate words
		$words = explode( ' ', $title );

		foreach ( $words as $key => $word ) {
			// If this word is the first, or it's not one of our small words, capitalise it
			// with ucwords().
			if ( $key == 0 || ! in_array( $word, $small_words ) ) {
				$words[ $key ] = ucwords( $word );
			}
		}

		// Join the words back into a string
		$new_title = implode( ' ', $words );

		return $new_title;
	}
}

if ( ! function_exists( 'br2nl' ) ) {
	/**
	 * Convert BR tags to nl
	 *
	 * @see http://php.net/manual/en/function.nl2br.php
	 * @param string The string to convert
	 * @return string The converted string
	 */
	function br2nl( $string ) {
		return preg_replace( '/\<br(\s*)?\/?\>/i', "\n", $string );
	}
}

if ( ! function_exists( 'first_char' ) ) {
	function first_char( $string ) {
		if ( strlen( $string ) == 0 ) {
			return '';
		}
		return substr( $string, 0, 1 );
	}
}

if ( ! function_exists( 'xssafe' ) ) {
	/**
	 * Xss mitigation functions (this requires php 5.4+)
	 *
	 * @see https://www.owasp.org/index.php/PHP_Security_Cheat_Sheet#XSS_Cheat_Sheet
	 */
	function xssafe( $data, $encoding = 'UTF-8' ) {
		return htmlspecialchars( $data,ENT_QUOTES | ENT_HTML401, $encoding );
	}
}

if ( ! function_exists( 'valid_email' ) ) {
	/**
	 * Replaced with php's email validator
	 */
	function valid_email( $email ) {
		return filter_var( $email, FILTER_VALIDATE_EMAIL );
	}
}
