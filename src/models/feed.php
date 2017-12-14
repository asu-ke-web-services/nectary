<?php

namespace Nectary;

interface Feed {
	public function retrieve_items();
	public function sort_by_date( string $order = 'asc' );
	public function get_items() : array;
	public function set_items( array $items );
	public function get_unique_items() : array;
}
