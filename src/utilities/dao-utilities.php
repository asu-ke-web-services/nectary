<?php

if ( ! function_exists( 'sanitize_order_by' ) ) {
	/**
	 * Sanitize an order by string, allows commas, periods,
	 * upper/lowercase alphanumerics. Anything else will
	 * make this function return an empty string
	 *
	 * Also allows sorting by `RAND()`.
	 *
	 * Examples:
	 * - `citations.date DESC`
	 * - `blah.id ASC, table2.id DESC`
	 *
	 * @param  string $string
	 * @return string
	 */
	function sanitize_order_by( $string ) {
		if ( strtolower( trim( rtrim( $string ) ) ) === 'rand()' ) {
			return 'RAND()'; }
		if ( strrpos( $string, ';' ) !== false ) {
			return ''; }
		if ( strrpos( $string, '\'' ) !== false ) {
			return ''; }
		if ( strrpos( $string, '\"' ) !== false ) {
			return ''; }
		if ( strrpos( $string, '\\' ) !== false ) {
			return ''; }
		if ( strrpos( $string, '&' ) !== false ) {
			return ''; }
		if ( strrpos( $string, '^' ) !== false ) {
			return ''; }
		if ( strrpos( $string, '<' ) !== false ) {
			return ''; }
		if ( strrpos( $string, '>' ) !== false ) {
			return ''; }
		if ( strrpos( $string, '(' ) !== false ) {
			return ''; }
		if ( strrpos( $string, ')' ) !== false ) {
			return ''; }
		return $string;
	}
}
